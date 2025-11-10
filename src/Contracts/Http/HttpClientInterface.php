<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Http;


use Illuminate\Http\Client\Response;

interface HttpClientInterface
{
    public function get(string $uri, array $query = []): Response;

    public function post(string $uri, array $data = []): Response;

    public function patch(string $uri, array $data = []): Response;

    public function put(string $uri, array $data = []): Response;

    public function delete(string $uri): Response;

    public function postRaw(string $uri, string $body, string $contentType): Response;

    public function destroy(string $uri, array $data): Response;
}
