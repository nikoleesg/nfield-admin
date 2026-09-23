<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SubscriptionCollectionEndpointInterface;

final class SubscriptionCollectionEndpoint extends BaseEndpoint implements SubscriptionCollectionEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/events/subscriptions";
    }

    public function list(): array
    {
        $uri = $this->basePath();

        return $this->httpClient->get($uri)->json();
    }

    public function create(array $data): array
    {
        $uri = $this->basePath();

        return $this->httpClient->post($uri, $data)->json();
    }
}
