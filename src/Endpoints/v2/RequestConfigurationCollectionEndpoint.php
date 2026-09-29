<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\RequestConfigurationCollectionEndpointInterface;

/**
 * `/v2/requests`: configurations for the scripting *REQUEST command, named so
 * they are not mistaken for HTTP requests.
 */
final class RequestConfigurationCollectionEndpoint extends BaseEndpoint implements RequestConfigurationCollectionEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/requests";
    }

    public function list(array $query = []): array
    {
        $uri = $this->basePath();
        $json = $this->httpClient->get($uri, $query)->json();

        // The spec documents this list as returning a single RequestModel
        // (and OData content types): accept one object, a bare list or the
        // {"value": [...]} envelope, and always hand back a list.
        if (is_array($json) && $json !== [] && ! array_is_list($json) && ! array_key_exists('value', $json)) {
            return [$json];
        }

        return $this->unwrapList($json);
    }

    public function create(array $requestConfigurationModel): void
    {
        $uri = $this->basePath();

        $this->httpClient->post($uri, $requestConfigurationModel);
    }

    public function update(array $requestConfigurationModel): array
    {
        $uri = $this->basePath();

        return $this->httpClient->put($uri, $requestConfigurationModel)->json();
    }
}
