<?php

namespace Nikoleesg\NfieldAdmin\Endpoints;

use Nikoleesg\NfieldAdmin\HttpClient;

class BaseEndpoint
{
    protected string $resourcePath;

    protected HttpClient $httpClient;

    public function __construct()
    {
        $this->httpClient = new HttpClient();
    }
}
