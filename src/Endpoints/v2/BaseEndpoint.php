<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Http\HttpClientInterface;
use Nikoleesg\NfieldAdmin\Traits\EndpointPath;

abstract class BaseEndpoint
{
    use EndpointPath;

    public function __construct(protected HttpClientInterface $httpClient)
    {
        $this->basePath = $this->buildPath();
    }

    abstract protected function buildPath(): string;
}
