<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface BackgroundActivitiesEndpointInterface
{
    /**
     * Retrieve details of a specific background activity.
     */
    public function get(string $activityId): array;
}
