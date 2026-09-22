<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\BackgroundActivitiesEndpointInterface;

final class BackgroundActivitiesEndpoint extends BaseEndpoint implements BackgroundActivitiesEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/BackgroundActivities";
    }

    public function get(string $activityId): array
    {
        $uri = $this->resourcePath($activityId);

        return $this->httpClient->get($uri)->json();
    }
}
