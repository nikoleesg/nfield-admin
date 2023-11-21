<?php

namespace Nikoleesg\NfieldAdmin;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Nikoleesg\NfieldAdmin\Endpoints\v1\SignInEndpoint;
use Nikoleesg\NfieldAdmin\Exceptions\AuthenticationException;

class HttpClient
{
    protected string $baseUrl = 'https://apiap.nfieldmr.com';

    protected Response $httpResponse;

    /**
     * To make a GET request
     */
    public function get(string $endpoint, bool $authorized = true): Response
    {
        $this->httpResponse = $this->request($authorized)
            ->get($endpoint);

        return $this->response();
    }

    /**
     * To make a POST request
     *
     * @param  array|null  $data
     */
    public function post(string $endpoint, bool $authorized = true, array $data = []): Response
    {
        $this->httpResponse = $this->request($authorized)
            ->post($endpoint, $data);

        return $this->response();
    }

    /**
     * To make a DELETE request
     */
    public function delete(string $endpoint, bool $authorized = true): Response
    {
        $this->httpResponse = $this->request($authorized)
            ->delete($endpoint);

        return $this->response();
    }

    /**
     * To make a PATCH request
     */
    public function patch(string $endpoint, bool $authorized = true, array $data = []): Response
    {
        $this->httpResponse = $this->request($authorized)
            ->patch($endpoint, $data);

        return $this->response();
    }

    /**
     * To make a PUT request
     */
    public function put(string $endpoint, bool $authorized = true, array $data = []): Response
    {
        $this->httpResponse = $this->request($authorized)
            ->put($endpoint, $data);

        return $this->response();
    }

    protected function request($authorized = true): PendingRequest
    {
        if ($authorized) {
            return Http::baseUrl($this->baseUrl)
                ->withHeader('Authorization', 'Basic '.$this->getAuthenticationToken());
        }

        return Http::baseUrl($this->baseUrl);
    }

    protected function response(): Response
    {
        //TODO: handling http error
        //        throw_if($this->httpResponse->forbidden(), new AuthenticationException());

        return $this->httpResponse;
    }

    /**
     * Return authentication token
     */
    protected function getAuthenticationToken(): string
    {
        $signIn = new SignInEndpoint();

        if (config('nfield-admin.cache_key')) {
            return cache()->remember(
                config('nfield-admin.key_name'),
                config('nfield-admin.expire_seconds'),

                function () use ($signIn) {
                    return $signIn();
                }
            );
        }

        return $signIn();
    }
}
