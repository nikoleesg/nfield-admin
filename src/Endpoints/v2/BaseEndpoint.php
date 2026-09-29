<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Http\HttpClientInterface;
use Nikoleesg\NfieldAdmin\Services\Http\ResponseKeyNormalizer;
use Nikoleesg\NfieldAdmin\Traits\EndpointPath;

abstract class BaseEndpoint
{
    use EndpointPath;

    /**
     * The API version segment every endpoint path starts with.
     */
    protected string $version = 'v2';

    public function __construct(protected HttpClientInterface $httpClient)
    {
        $this->basePath = $this->buildPath();
    }

    abstract protected function buildPath(): string;

    /**
     * Unwrap the OData `{"value": [...]}` envelope some list responses use.
     *
     * Key casing is already normalized at the HTTP boundary (see
     * {@see ResponseKeyNormalizer}).
     *
     * @return list<array<string, mixed>>
     */
    protected function unwrapList(mixed $json): array
    {
        if (! is_array($json)) {
            return [];
        }

        if (! array_is_list($json) && isset($json['value']) && is_array($json['value'])) {
            $json = $json['value'];
        }

        return array_is_list($json) ? $json : [];
    }
}
