<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityResponseModel;
use Nikoleesg\NfieldAdmin\Data\Roles\PermissionModel;
use Nikoleesg\NfieldAdmin\Data\Roles\UserRoleModel;

/**
 * NfieldManagerService - Main entry point for Nfield Admin SDK
 *
 * Provides fluent interface for managing surveys, sampling points, fieldwork,
 * quotas, CAPI interviewers, and background activities via the Nfield API v2.
 *
 * Usage:
 * ```
 * $manager = app(NfieldManagerService::class);
 * $surveys = $manager->surveys()->list();
 * $manager->surveys()->forSurvey('survey-id')->fieldwork()->start();
 * $interviewers = $manager->capiInterviewers()->list();
 * ```
 */
class NfieldManagerService
{
    public function __construct(
        protected SurveyService $surveyService,
        protected CapiInterviewerService $capiInterviewerService,
        protected BackgroundActivitiesService $backgroundActivitiesService,
        protected RoleService $roleService,
        protected EventSubscriptionService $eventSubscriptionService,
    ) {}

    // ========================================
    // Explicit Method Declarations
    // ========================================

    public function surveys(): SurveyService
    {
        return $this->surveyService;
    }

    /**
     * Get status of a background activity (async operation).
     *
     * Used to track long-running operations like data downloads.
     *
     * @param  string  $activityId  Background activity ID
     * @return BackgroundActivityResponseModel Activity status and details
     */
    public function getBackgroundActivity(string $activityId): BackgroundActivityResponseModel
    {
        return $this->backgroundActivitiesService->getBackgroundActivity($activityId);
    }

    // ========================================
    // CAPI Interviewer Methods
    // ========================================

    public function capiInterviewers(): CapiInterviewerService
    {
        return $this->capiInterviewerService;
    }

    // ========================================
    // Event Subscriptions
    // ========================================

    public function eventSubscriptions(): EventSubscriptionService
    {
        return $this->eventSubscriptionService;
    }

    // ========================================
    // Role Methods
    // ========================================

    /**
     * Get the current user's role and permissions.
     */
    public function getUserRole(): UserRoleModel
    {
        return $this->roleService->getUserRole();
    }

    /**
     * Get all roles and their associated permissions.
     *
     * @return Collection<string, Collection<int, PermissionModel>>
     */
    public function listRoles(): Collection
    {
        return $this->roleService->listRoles();
    }
}
