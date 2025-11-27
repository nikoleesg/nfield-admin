<?php

namespace Nikoleesg\NfieldAdmin\Services\Http;

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
        '/v2/token'
    ];

    private PendingRequest $http;

    public function __construct(private PendingRequest $httpClient)
    {
        $this->http = $httpClient;

        $this->baseUrl = config('nfield-admin.base_url');
    }

    public function get(string $uri, array $query = []): Response
    {
        return $this->request(fn() => $this->http->get($uri, $query), $uri);
    }

    public function post(string $uri, array $data = []): Response
    {
        return $this->request(fn() => $this->http->post($uri, $data), $uri);
    }

    public function patch(string $uri, array $data = []): Response
    {
        return $this->request(fn() => $this->http->patch($uri, $data), $uri);
    }

    public function put(string $uri, array $data = []): Response
    {
        return $this->request(fn() => $this->http->put($uri, $data), $uri);
    }

    public function delete(string $uri, array $data = []): Response
    {
        return $this->request(fn() => $this->http->delete($uri, $data), $uri);
    }

    public function postRaw(string $uri, string $body, string $contentType): Response
    {
        try {
            $this->http
                ->baseUrl($this->baseUrl)
                ->when(!in_array($uri, $this->skipAuth), function ($request) {
                    $request->withToken($this->token());
                });

            $response = $this->http
                ->withBody($body, $contentType)
                ->post($uri);

            $response->throw();

            return $response;

        } catch (RequestException $exception) {
            throw new ApiRequestException(
                $exception->getMessage(),
                $exception->response?->status(),
                $exception
            );
        }
    }

    public function destroy(string $uri, array $data): Response
    {
        return $this->request(fn() => $this->http->delete($uri, $data), $uri);
    }

    private function request(callable $call, string $uri): Response
    {
        try {
            $this->http
                ->baseUrl($this->baseUrl)
                ->when(!in_array($uri, $this->skipAuth), function ($request) {
                    $request->withToken($this->token());
                })
                ->withHeader('Content-Type', 'application/json');

            $response = $call();

            $response->throw();

            return $response;

        } catch (RequestException $exception) {
            throw new ApiRequestException(
                $exception->getMessage(),
                $exception->response?->status(),
                $exception
            );
        }
    }

    private function token(): string
    {
        $accessToken = $this->getAccessToken();

        $shouldCacheKey = config('nfield-admin.cache_key');

        if (!$shouldCacheKey) {
            return $accessToken['AccessToken'];
        }

        $cacheKey = config('nfield-admin.cache_key_prefix'. 'nfield_') . 'access_token';

        return Cache::remember($cacheKey, $accessToken['ExpiresIn'] - 5, function () use ($accessToken) {
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
            "domainName" => config('nfield-admin.Domain'),
            "userName"   => config('nfield-admin.Username'),
            "password"   => config('nfield-admin.Password'),
        ];
    }
}
