<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\BackgroundActivitiesEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityResponseModel;

class BackgroundActivitiesService
{
    public function __construct(
        protected BackgroundActivitiesEndpointInterface $backgroundActivitiesEndpoint,
    ) {}

    /**
     * Get status of a background activity (async operation).
     *
     * Used to track long-running operations like data downloads.
     *
     * @param  string  $activityId  Background activity ID
     * @return BackgroundActivityResponseModel Activity status and details
     */
    public function get(string $activityId): BackgroundActivityResponseModel
    {
        return BackgroundActivityResponseModel::from($this->backgroundActivitiesEndpoint->get($activityId));
    }
}
