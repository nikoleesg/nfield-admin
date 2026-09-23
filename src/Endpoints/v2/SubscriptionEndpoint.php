<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SubscriptionEndpointInterface;

final class SubscriptionEndpoint extends BaseEndpoint implements SubscriptionEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/events/subscriptions";
    }

    public function get(string $name): array
    {
        $uri = $this->resourcePath($name);

        return $this->httpClient->get($uri)->json();
    }

    public function updatePartial(string $name, array $data): void
    {
        $uri = $this->resourcePath($name);

        $this->httpClient->patch($uri, $data);
    }

    public function destroy(string $name): void
    {
        $uri = $this->resourcePath($name);

        $this->httpClient->delete($uri);
    }
}
