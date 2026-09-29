<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Http;

use Illuminate\Http\Client\Response;

interface HttpClientInterface
{
    /**
     * @param  array<string, mixed>  $query
     */
    public function get(string $uri, array $query = []): Response;

    /**
     * @param  array<mixed>  $data
     */
    public function post(string $uri, array $data = []): Response;

    /**
     * @param  array<mixed>  $data
     */
    public function patch(string $uri, array $data = []): Response;

    /**
     * @param  array<mixed>  $data
     */
    public function put(string $uri, array $data = []): Response;

    /**
     * @param  array<mixed>  $data
     */
    public function delete(string $uri, array $data = []): Response;

    public function postRaw(string $uri, string $body, string $contentType): Response;

    public function postMultipart(string $uri, string $name, string $contents, string $filename): Response;
}
