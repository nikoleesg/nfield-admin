<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

/**
 * Background activities, reached through `NfieldManager::backgroundActivities()`.
 *
 * The API has no list of activities; an activity is reached by the id an
 * asynchronous operation (data download, sample upload, publish, ...) returns.
 * Services mirror the endpoint naming: one activity is
 * {@see BackgroundActivitiesService}, which pairs with BackgroundActivitiesEndpoint.
 */
class BackgroundActivitiesCollectionService
{
    /**
     * One background activity.
     */
    public function forActivity(string $activityId): BackgroundActivitiesService
    {
        return app(BackgroundActivitiesService::class)->setActivityId($activityId);
    }
}
