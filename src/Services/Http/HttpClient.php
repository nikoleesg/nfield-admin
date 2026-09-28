<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services\Http;

use Illuminate\Contracts\Cache\Lock;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Nikoleesg\NfieldAdmin\Contracts\Http\HttpClientInterface;
use Nikoleesg\NfieldAdmin\Exceptions\ApiRequestException;
use Nikoleesg\NfieldAdmin\Exceptions\AuthenticationException;
use Nikoleesg\NfieldAdmin\Exceptions\InvalidConfigurationException;
use Nikoleesg\NfieldAdmin\Exceptions\NotFoundException;
use Nikoleesg\NfieldAdmin\Exceptions\ValidationException;

class HttpClient implements HttpClientInterface
{
    private string $baseUrl;

    private array $skipAuth = [
        '/v2/token',
        '/v2/token/refresh',
    ];

    /**
     * Paths whose response keys must be left exactly as the API returned them.
     *
     * Response keys are normalized to camelCase everywhere else (see
     * {@see ResponseKeyNormalizer}), which would corrupt any response that is a
     * dictionary keyed by user data. An audit of all 282 v2 operations found
     * exactly one such response: `GET /v2/roles`, a map of role name to
     * permissions. It is listed here ahead of being implemented so the
     * normalization cannot silently mangle it later.
     *
     * @var list<string> case-insensitive regular expressions matched against the request URI
     */
    private array $rawResponsePaths = [
        '#^/?v2/roles$#i',
    ];

    public function __construct(private Factory $factory)
    {
        $this->baseUrl = config('nfield-admin.base_url');
    }

    private function getPendingRequest(string $uri): PendingRequest
    {
        $request = $this->factory
            ->baseUrl($this->baseUrl)
            ->acceptJson()
            ->timeout((int) config('nfield-admin.http.timeout', 30))
            ->connectTimeout((int) config('nfield-admin.http.connect_timeout', 10));

        $retries = (int) config('nfield-admin.http.retries', 3);
        $retryDelay = (int) config('nfield-admin.http.retry_delay', 100);

        if ($retries > 1) {
            $request->retry(
                $retries,
                function (int $attempt, ?\Throwable $exception) use ($retryDelay): int {
                    if ($exception instanceof RequestException && $exception->response !== null) {
                        $retryAfter = $exception->response->header('Retry-After');
                        if ($retryAfter !== null && $retryAfter !== '') {
                            if (is_numeric($retryAfter)) {
                                return (int) $retryAfter * 1000;
                            }
                            $time = strtotime($retryAfter);
                            if ($time !== false) {
                                return max(0, ($time - time()) * 1000);
                            }
                        }
                    }

                    return (int) ($retryDelay * (2 ** ($attempt - 1)));
                },
                function (\Throwable $exception): bool {
                    if ($exception instanceof ConnectionException) {
                        return true;
                    }

                    if ($exception instanceof RequestException) {
                        $status = $exception->response->status();

                        return $status === 429 || ($status >= 500 && $status < 600);
                    }

                    return false;
                },
                throw: false
            );
        }

        if (! in_array($uri, $this->skipAuth, true)) {
            $request->withToken($this->token());
        }

        return $request;
    }

    public function get(string $uri, array $query = []): Response
    {
        return $this->request($uri, fn () => $this->getPendingRequest($uri)->get($uri, $query));
    }

    public function post(string $uri, array $data = []): Response
    {
        return $this->request($uri, fn () => $this->getPendingRequest($uri)->post($uri, $data));
    }

    public function patch(string $uri, array $data = []): Response
    {
        return $this->request($uri, fn () => $this->getPendingRequest($uri)->patch($uri, $data));
    }

    public function put(string $uri, array $data = []): Response
    {
        return $this->request($uri, fn () => $this->getPendingRequest($uri)->put($uri, $data));
    }

    public function delete(string $uri, array $data = []): Response
    {
        return $this->request($uri, fn () => $this->getPendingRequest($uri)->delete($uri, $data));
    }

    public function postRaw(string $uri, string $body, string $contentType): Response
    {
        return $this->request($uri, fn () => $this->getPendingRequest($uri)
            ->withBody($body, $contentType)
            ->post($uri)
        );
    }

    public function postMultipart(string $uri, string $name, string $contents, string $filename): Response
    {
        return $this->request($uri, fn () => $this->getPendingRequest($uri)
            ->attach($name, $contents, $filename)
            ->post($uri)
        );
    }

    private function request(string $uri, callable $call, bool $retry = true): Response
    {
        try {
            $response = $call();

            $response->throw();

            return $this->shouldNormalize($uri)
                ? NormalizedResponse::wrap($response)
                : $response;

        } catch (ConnectionException $exception) {
            throw new ApiRequestException(
                $exception->getMessage(),
                0,
                null,
                $exception
            );
        } catch (RequestException $exception) {
            $response = $exception->response;
            $status = $response->status();

            if ($status === 401) {
                if ($retry && ! in_array($uri, $this->skipAuth, true)) {
                    $this->forgetToken();

                    return $this->request($uri, $call, false);
                }
                throw new AuthenticationException($exception->getMessage(), $status, $response, $exception);
            }

            if ($status === 404) {
                throw new NotFoundException($exception->getMessage(), $status, $response, $exception);
            }

            if ($status === 422) {
                throw new ValidationException($exception->getMessage(), $status, $response, $exception);
            }

            throw new ApiRequestException(
                $exception->getMessage(),
                $status,
                $response,
                $exception
            );
        }
    }

    /**
     * Whether the decoded response for this URI should have its keys normalized.
     */
    private function shouldNormalize(string $uri): bool
    {
        foreach ($this->rawResponsePaths as $pattern) {
            if (preg_match($pattern, $uri) === 1) {
                return false;
            }
        }

        return true;
    }

    private function token(): string
    {
        $cache = $this->cache();

        if (! $cache instanceof Repository) {
            return $this->getAccessToken()['accessToken'];
        }

        $cacheKey = $this->cacheKey('access_token');

        $cached = $cache->get($cacheKey);

        if ($cached && isset($cached['accessToken'])) {
            return $cached['accessToken'];
        }

        $lock = $this->acquireLock($cache, $this->cacheKey('lock:token'));

        if ($lock !== null) {
            try {
                return $lock->block(10, function () use ($cache, $cacheKey): string {
                    $cached = $cache->get($cacheKey);

                    if ($cached && isset($cached['accessToken'])) {
                        return $cached['accessToken'];
                    }

                    return $this->fetchAndCacheToken($cache, $cacheKey);
                });
            } catch (LockTimeoutException) {
                // If lock timed out, fall back to fetching token directly
            }
        }

        return $this->fetchAndCacheToken($cache, $cacheKey);
    }

    private function fetchAndCacheToken(Repository $cache, string $cacheKey): string
    {
        $tokenData = $this->getAccessToken();

        $expiresIn = $tokenData['expiresIn'] ?? 3600;
        $maxTtl = config('nfield-admin.cache.ttl', 600);
        $ttl = min(max($expiresIn - 30, 0), $maxTtl);

        $cache->put($cacheKey, ['accessToken' => $tokenData['accessToken']], $ttl);

        if (! empty($tokenData['refreshToken'])) {
            $cache->put($this->cacheKey('refresh_token'), $tokenData['refreshToken'], 60 * 60 * 24 * 14); // 14 days
        }

        return $tokenData['accessToken'];
    }

    private function acquireLock(Repository $cache, string $key, int $seconds = 15): ?Lock
    {
        try {
            if (method_exists($cache, 'lock')) {
                return $cache->lock($key, $seconds);
            }
        } catch (\BadMethodCallException) {
            // Cache store does not support locking (e.g. file driver)
        }

        return null;
    }

    private function getAccessToken(): array
    {
        $cache = $this->cache();

        $refreshToken = $cache?->get($this->cacheKey('refresh_token'));

        if ($refreshToken) {
            try {
                $response = $this->post('/v2/token/refresh', [
                    'refreshToken' => $refreshToken,
                ]);
                $response->throw();

                $data = $response->json();

                return [
                    'accessToken' => $data['accessToken'] ?? '',
                    'refreshToken' => $data['refreshToken'] ?? '',
                    'expiresIn' => $data['expiresIn'] ?? 3600,
                ];
            } catch (ApiRequestException $e) {
                Log::warning('Nfield token refresh failed, falling back to credentials: '.$e->getMessage());
                $cache->forget($this->cacheKey('refresh_token'));
            }
        }

        $response = $this->post('/v2/token', $this->getCredentials());
        $response->throw();

        $data = $response->json();

        return [
            'accessToken' => $data['accessToken'] ?? '',
            'refreshToken' => $data['refreshToken'] ?? '',
            'expiresIn' => $data['expiresIn'] ?? 3600,
        ];
    }

    private function forgetToken(): void
    {
        $cache = $this->cache();

        $cache?->forget($this->cacheKey('access_token'));
        $cache?->forget($this->cacheKey('refresh_token'));
    }

    /**
     * The cache repository used for tokens, or null when caching is disabled.
     *
     * A null store name resolves to the host application's default store — any
     * PSR-16 compatible driver can hold a token string.
     */
    private function cache(): ?Repository
    {
        if (! config('nfield-admin.cache.enabled', true)) {
            return null;
        }

        return Cache::store(config('nfield-admin.cache.store'));
    }

    public function cacheKey(string $name): string
    {
        $domain = (string) config('nfield-admin.domain');
        $username = (string) config('nfield-admin.username');
        $baseUrl = (string) config('nfield-admin.base_url', $this->baseUrl);

        $hash = substr(hash('sha256', "{$domain}|{$username}|{$baseUrl}"), 0, 16);

        return config('nfield-admin.cache.prefix', 'nfield_').$hash.':'.$name;
    }

    /**
     * @return array{domainName: string, userName: string, password: string}
     */
    private function getCredentials(): array
    {
        $domain = config('nfield-admin.domain');
        $username = config('nfield-admin.username');
        $password = config('nfield-admin.password');

        $missing = [];
        if (! is_string($domain) || trim($domain) === '') {
            $missing[] = 'domain';
        }
        if (! is_string($username) || trim($username) === '') {
            $missing[] = 'username';
        }
        if (! is_string($password) || trim($password) === '') {
            $missing[] = 'password';
        }

        if ($missing !== []) {
            throw InvalidConfigurationException::missingCredentials($missing);
        }

        return [
            'domainName' => $domain,
            'userName' => $username,
            'password' => $password,
        ];
    }
}
