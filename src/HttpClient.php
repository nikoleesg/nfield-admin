<?php

namespace Nikoleesg\NfieldAdmin;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Nikoleesg\NfieldAdmin\Endpoints\v1\SignInEndpoint;
use Nikoleesg\NfieldAdmin\Exceptions\AuthenticationException;

class HttpClient
{
    protected string $baseUrl = "https://apiap.nfieldmr.com";

    protected Response $httpResponse;

    /**
     * To make a GET request
     *
     * @param string $endpoint
     * @param bool $authorized
     * @return Response
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
     * @param string $endpoint
     * @param bool $authorized
     * @param array|null $data
     * @return Response
     */
    public function post(string $endpoint, bool $authorized = true, array $data = []): Response
    {
        $this->httpResponse = $this->request($authorized)
            ->post($endpoint, $data);

        return $this->response();
    }

    /**
     * To make a DELETE request
     *
     * @param string $endpoint
     * @param bool $authorized
     * @return Response
     */
    public function delete(string $endpoint, bool $authorized = true): Response
    {
        $this->httpResponse = $this->request($authorized)
            ->delete($endpoint);

        return $this->response();
    }

    /**
     * To make a PATCH request
     *
     * @param string $endpoint
     * @param bool $authorized
     * @param array $data
     * @return Response
     */
    public function patch(string $endpoint, bool $authorized = true, array $data = []): Response
    {
        $this->httpResponse = $this->request($authorized)
            ->patch($endpoint, $data);

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
     * @return string
     */
    protected function getAuthenticationToken(): string
    {
        $signIn = new SignInEndpoint();

        if (config('nfield-admin.cache_key'))
        {
            return cache()->remember(
                config('nfield-admin.key_name'),
                config('nfield-admin.expire_seconds'),

                function() use ($signIn) {
                    return $signIn();
                }
            );
        }

        return $signIn();
    }
}
