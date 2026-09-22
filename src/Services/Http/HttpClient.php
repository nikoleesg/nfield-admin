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

class HttpClient implements HttpClientInterface
{
    private string $baseUrl;

    private array $skipAuth = [
        '/v2/token',
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

    private function request(callable $call): Response
    {
        try {
            $response = $call();

            $response->throw();

            return $response;

        } catch (RequestException $exception) {
            throw new ApiRequestException(
                $exception->getMessage(),
                $exception->response->status(),
                $exception
            );
        }
    }

    private function token(): string
    {
        $shouldCache = config('nfield-admin.cache_key', true);

        if (! $shouldCache) {
            return $this->getAccessToken()['AccessToken'];
        }

        $cacheKeyPrefix = config('nfield-admin.cache_key_prefix', 'nfield_');
        $cacheKey = $cacheKeyPrefix.'access_token';

        $ttl = config('nfield-admin.expire_seconds', 600);

        return Cache::remember($cacheKey, $ttl, function () {
            $accessToken = $this->getAccessToken();

            return $accessToken['AccessToken'];
        });
    }

    private function getAccessToken(): array
    {
        $response = $this->post('/v2/token', $this->getCredentials());

        $response->throw();

        return $response->json();
    }

    private function getCredentials(): array
    {
        return [
            'domainName' => config('nfield-admin.Domain'),
            'userName' => config('nfield-admin.Username'),
            'password' => config('nfield-admin.Password'),
        ];
    }
}
