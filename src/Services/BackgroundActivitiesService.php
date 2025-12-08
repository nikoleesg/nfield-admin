<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\BackgroundActivitiesEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityResponseModel;

class BackgroundActivitiesService
{
    public function __construct(
        protected BackgroundActivitiesEndpointInterface $backgroundActivitiesEndpoint,
    ) {}

    public function getBackgroundActivity(string $activityId): BackgroundActivityResponseModel
    {
        return BackgroundActivityResponseModel::from($this->backgroundActivitiesEndpoint->get($activityId));
    }
}
