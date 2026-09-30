<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\BackgroundActivitiesEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\BackgroundActivityScopedInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityResponseModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToBackgroundActivity;

/**
 * One background activity, reached through
 * `NfieldManager::backgroundActivities()->forActivity($activityId)`.
 *
 * Services mirror the endpoint naming: this pairs with BackgroundActivitiesEndpoint.
 */
class BackgroundActivitiesService implements BackgroundActivityScopedInterface
{
    use ScopedToBackgroundActivity;

    public function __construct(
        protected BackgroundActivitiesEndpointInterface $backgroundActivitiesEndpoint,
    ) {}

    /**
     * The activity's status and details; poll until it finishes.
     */
    public function get(): BackgroundActivityResponseModel
    {
        return BackgroundActivityResponseModel::from($this->backgroundActivitiesEndpoint->get($this->getActivityId()));
    }
}
