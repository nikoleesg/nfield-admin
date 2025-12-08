<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface BackgroundActivitiesEndpointInterface
{
    /**
     * Retrieve details of a specific background activity.
     * @param string $activityId
     * @return array
     */
    public function get(string $activityId): array;

}
