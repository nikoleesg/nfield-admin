<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services\Http;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Nikoleesg\NfieldAdmin\Contracts\Http\HttpClientInterface;
use Nikoleesg\NfieldAdmin\Exceptions\ApiRequestException;
use Nikoleesg\NfieldAdmin\Exceptions\AuthenticationException;
use Nikoleesg\NfieldAdmin\Exceptions\NotFoundException;
use Nikoleesg\NfieldAdmin\Exceptions\ValidationException;

class HttpClient implements HttpClientInterface
{
    private string $baseUrl;

    private array $skipAuth = [
        '/v2/token',
        '/v2/token/refresh',
    ];

    public function __construct(private Factory $factory)
    {
        $this->baseUrl = config('nfield-admin.base_url');
    }

    private function getPendingRequest(string $uri): PendingRequest
    {
        $request = $this->factory
            ->baseUrl($this->baseUrl)
            ->withHeader('Content-Type', 'application/json');

        if (! in_array($uri, $this->skipAuth)) {
            $request->withToken($this->token());
        }

        return $request;
    }

    public function get(string $uri, array $query = []): Response
    {
        return $this->request(fn () => $this->getPendingRequest($uri)->get($uri, $query));
    }

    public function post(string $uri, array $data = []): Response
    {
        return $this->request(fn () => $this->getPendingRequest($uri)->post($uri, $data));
    }

    public function patch(string $uri, array $data = []): Response
    {
        return $this->request(fn () => $this->getPendingRequest($uri)->patch($uri, $data));
    }

    public function put(string $uri, array $data = []): Response
    {
        return $this->request(fn () => $this->getPendingRequest($uri)->put($uri, $data));
    }

    public function delete(string $uri, array $data = []): Response
    {
        return $this->request(fn () => $this->getPendingRequest($uri)->delete($uri, $data));
    }

    public function postRaw(string $uri, string $body, string $contentType): Response
    {
        return $this->request(function () use ($uri, $body, $contentType) {
            $request = $this->factory
                ->baseUrl($this->baseUrl)
                ->withBody($body, $contentType);

            if (! in_array($uri, $this->skipAuth)) {
                $request->withToken($this->token());
            }

            return $request->post($uri);
        });
    }

    public function postMultipart(string $uri, string $name, string $contents, string $filename): Response
    {
        return $this->request(function () use ($uri, $name, $contents, $filename) {
            $request = $this->factory
                ->baseUrl($this->baseUrl)
                ->attach($name, $contents, $filename);

            if (! in_array($uri, $this->skipAuth)) {
                $request->withToken($this->token());
            }

            return $request->post($uri);
        });
    }

    private function request(callable $call, bool $retry = true): Response
    {
        try {
            $response = $call();

            $response->throw();

            return $response;

        } catch (RequestException $exception) {
            $response = $exception->response;
            $status = $response->status();

            if ($status === 401) {
                if ($retry) {
                    $this->forgetToken();

                    return $this->request($call, false);
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

    private function token(): string
    {
        $shouldCache = config('nfield-admin.cache.enabled', true);
        $isRedis = config('cache.default') === 'redis';

        if (! $shouldCache || ! $isRedis) {
            return $this->getAccessToken()['accessToken'];
        }

        $cacheKeyPrefix = config('nfield-admin.cache.prefix', 'nfield_');
        $cacheKey = $cacheKeyPrefix.'access_token';
        $refreshCacheKey = $cacheKeyPrefix.'refresh_token';

        $cached = Cache::store('redis')->get($cacheKey);

        if ($cached && isset($cached['accessToken'])) {
            return $cached['accessToken'];
        }

        $tokenData = $this->getAccessToken();

        $expiresIn = $tokenData['expiresIn'] ?? 3600;
        $maxTtl = config('nfield-admin.cache.ttl', 600);
        $ttl = min(max($expiresIn - 30, 0), $maxTtl);

        Cache::store('redis')->put($cacheKey, ['accessToken' => $tokenData['accessToken']], $ttl);

        if (! empty($tokenData['refreshToken'])) {
            Cache::store('redis')->put($refreshCacheKey, $tokenData['refreshToken'], 60 * 60 * 24 * 14); // 14 days
        }

        return $tokenData['accessToken'];
    }

    private function getAccessToken(): array
    {
        $shouldCache = config('nfield-admin.cache.enabled', true);
        $isRedis = config('cache.default') === 'redis';
        $cacheKeyPrefix = config('nfield-admin.cache.prefix', 'nfield_');
        $refreshCacheKey = $cacheKeyPrefix.'refresh_token';

        $refreshToken = null;
        if ($shouldCache && $isRedis) {
            $refreshToken = Cache::store('redis')->get($refreshCacheKey);
        }

        if ($refreshToken) {
            try {
                $response = $this->post('/v2/token/refresh', [
                    'refreshToken' => $refreshToken,
                ]);
                $response->throw();

                $data = $response->json();

                return [
                    'accessToken' => $data['accessToken'] ?? $data['AccessToken'] ?? '',
                    'refreshToken' => $data['refreshToken'] ?? $data['RefreshToken'] ?? '',
                    'expiresIn' => $data['expiresIn'] ?? $data['ExpiresIn'] ?? 3600,
                ];
            } catch (\Exception $e) {
                // Ignore and fall back to normal token
            }
        }

        $response = $this->post('/v2/token', $this->getCredentials());
        $response->throw();

        $data = $response->json();

        return [
            'accessToken' => $data['accessToken'] ?? $data['AccessToken'] ?? '',
            'refreshToken' => $data['refreshToken'] ?? $data['RefreshToken'] ?? '',
            'expiresIn' => $data['expiresIn'] ?? $data['ExpiresIn'] ?? 3600,
        ];
    }

    private function forgetToken(): void
    {
        $shouldCache = config('nfield-admin.cache.enabled', true);
        $isRedis = config('cache.default') === 'redis';

        if ($shouldCache && $isRedis) {
            $cacheKeyPrefix = config('nfield-admin.cache.prefix', 'nfield_');
            $cacheKey = $cacheKeyPrefix.'access_token';
            $refreshCacheKey = $cacheKeyPrefix.'refresh_token';

            Cache::store('redis')->forget($cacheKey);
            Cache::store('redis')->forget($refreshCacheKey);
        }
    }

    private function getCredentials(): array
    {
        return [
            'domainName' => config('nfield-admin.domain'),
            'userName' => config('nfield-admin.username'),
            'password' => config('nfield-admin.password'),
        ];
    }
}
