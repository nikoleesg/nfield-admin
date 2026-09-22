<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface BackgroundActivitiesEndpointInterface
{
    /**
     * Retrieve details of a specific background activity.
     */
    public function get(string $activityId): array;
}
