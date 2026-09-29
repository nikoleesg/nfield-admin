<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\RequestConfigurationEndpointInterface;

/**
 * `/v2/requests/{requestId}`: one *REQUEST command configuration.
 */
final class RequestConfigurationEndpoint extends BaseEndpoint implements RequestConfigurationEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/requests";
    }

    public function get(int $requestId): array
    {
        $uri = $this->resourcePath((string) $requestId);

        return $this->httpClient->get($uri)->json();
    }

    public function delete(int $requestId): void
    {
        $uri = $this->resourcePath((string) $requestId);

        $this->httpClient->delete($uri);
    }
}
