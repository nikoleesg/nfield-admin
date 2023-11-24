<?php

namespace Nikoleesg\NfieldAdmin;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
        try {
            $this->httpResponse = $this->request($authorized)
                ->get($endpoint);

            // Log api request
            if (config('nfield-admin.logging.enable')) {

                $log = [
                    'uri'           => $endpoint,
                    'method'        => 'GET',
                    'request_body'  => $data ?? null,
                    'status_code'   => $this->response()->status(),
                    'response_body' => $this->response()->body()
                ];

                if ($this->response()->successful()) {
                    Log::channel(config('nfield-admin.logging.channel'))->info('API request successful', $log);
                } else {
                    Log::channel(config('nfield-admin.logging.channel'))->notice('API request failed', $log);
                }

            }

        } catch (\Exception $exception) {
            Log::channel(config('nfield-admin.logging.channel'))->error('API request error.', ['exception' => $exception]);
        }

        return $this->response();
    }

    /**
     * To make a POST request
     *
     * @param array|null $data
     */
    public function post(string $endpoint, bool $authorized = true, array $data = []): Response
    {
        try {
            $this->httpResponse = $this->request($authorized)
                ->post($endpoint, $data);

            // Log api request
            if (config('nfield-admin.logging.enable')) {

                $log = [
                    'uri'           => $endpoint,
                    'method'        => 'POST',
                    'request_body'  => $data ?? null,
                    'status_code'   => $this->response()->status(),
                    'response_body' => $this->response()->body()
                ];

                if ($this->response()->successful()) {
                    Log::channel(config('nfield-admin.logging.channel'))->info('API request successful', $log);
                } else {
                    Log::channel(config('nfield-admin.logging.channel'))->notice('API request failed', $log);
                }
            }

        } catch (\Exception $exception) {
            Log::channel(config('nfield-admin.logging.channel'))->error('API request error.', ['exception' => $exception]);
        }
        return $this->response();
    }

    /**
     * To make a DELETE request
     */
    public function delete(string $endpoint, bool $authorized = true, array $data = []): Response
    {
        try {
            $this->httpResponse = $this->request($authorized)
                ->delete($endpoint, $data);

            // Log api request
            if (config('nfield-admin.logging.enable')) {

                $log = [
                    'uri'           => $endpoint,
                    'method'        => 'DELETE',
                    'request_body'  => $data ?? null,
                    'status_code'   => $this->response()->status(),
                    'response_body' => $this->response()->body()
                ];

                if ($this->response()->successful()) {
                    Log::channel(config('nfield-admin.logging.channel'))->info('API request successful', $log);
                } else {
                    Log::channel(config('nfield-admin.logging.channel'))->notice('API request failed', $log);
                }
            }

        } catch (\Exception $exception) {
            Log::channel(config('nfield-admin.logging.channel'))->error('API request error.', ['exception' => $exception]);
        }

        return $this->response();
    }

    /**
     * To make a PATCH request
     */
    public function patch(string $endpoint, bool $authorized = true, array $data = []): Response
    {
        try {
            $this->httpResponse = $this->request($authorized)
                ->patch($endpoint, $data);

            // Log api request
            if (config('nfield-admin.logging.enable')) {

                $log = [
                    'uri'           => $endpoint,
                    'method'        => 'PATCH',
                    'request_body'  => $data ?? null,
                    'status_code'   => $this->response()->status(),
                    'response_body' => $this->response()->body()
                ];

                if ($this->response()->successful()) {
                    Log::channel(config('nfield-admin.logging.channel'))->info('API request successful', $log);
                } else {
                    Log::channel(config('nfield-admin.logging.channel'))->notice('API request failed', $log);
                }
            }

        } catch (\Exception $exception) {
            Log::channel(config('nfield-admin.logging.channel'))->error('API request error.', ['exception' => $exception]);
        }

        return $this->response();
    }

    /**
     * To make a PUT request
     */
    public function put(string $endpoint, bool $authorized = true, array $data = []): Response
    {
        try {
            $this->httpResponse = $this->request($authorized)
                ->put($endpoint, $data);

            // Log api request
            if (config('nfield-admin.logging.enable')) {

                $log = [
                    'uri'           => $endpoint,
                    'method'        => 'PUT',
                    'request_body'  => $data ?? null,
                    'status_code'   => $this->response()->status(),
                    'response_body' => $this->response()->body()
                ];

                if ($this->response()->successful()) {
                    Log::channel(config('nfield-admin.logging.channel'))->info('API request successful', $log);
                } else {
                    Log::channel(config('nfield-admin.logging.channel'))->notice('API request failed', $log);
                }
            }

        } catch (\Exception $exception) {
            Log::channel(config('nfield-admin.logging.channel'))->error('API request error.', ['exception' => $exception]);
        }

        return $this->response();
    }

    protected function request($authorized = true): PendingRequest
    {
        if ($authorized) {
            return Http::baseUrl($this->baseUrl)
                ->withHeader('Authorization', 'Basic ' . $this->getAuthenticationToken())
                ->withHeader('Content-Type', 'application/json');
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
