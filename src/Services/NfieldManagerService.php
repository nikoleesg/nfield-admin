<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityResponseModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerResponseModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\NewCapiInterviewerRequestModel;
use Nikoleesg\NfieldAdmin\Data\Roles\PermissionModel;
use Nikoleesg\NfieldAdmin\Data\Roles\UserRoleModel;
use Nikoleesg\NfieldAdmin\Resources\CapiInterviewerResource;

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
 * $interviewers = $manager->listCapiInterviewers();
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

    /**
     * List all CAPI interviewers.
     *
     * @return Collection<int, CapiInterviewerModel> Collection of all CAPI interviewers
     */
    public function listCapiInterviewers(): Collection
    {
        return $this->capiInterviewerService->listCapiInterviewers();
    }

    /**
     * Find CAPI interviewers matching filter criteria.
     *
     * @param  array  $filter  Filter criteria
     * @return Collection<int, CapiInterviewerModel> Filtered collection of CAPI interviewers
     */
    public function findCapiInterviewers(array $filter = []): Collection
    {
        return $this->capiInterviewerService->findCapiInterviewers($filter);
    }

    /**
     * Create a new CAPI interviewer.
     *
     * @param  array|NewCapiInterviewerRequestModel  $data  CAPI interviewer data
     * @return CapiInterviewerResponseModel Created CAPI interviewer
     */
    public function createCapiInterviewer(array|NewCapiInterviewerRequestModel $data): CapiInterviewerResponseModel
    {
        return $this->capiInterviewerService->createCapiInterviewer($data);
    }

    /**
     * Get a CAPI interviewer by client interviewer ID.
     *
     * @param  string  $clientInterviewerId  Client interviewer ID
     * @return CapiInterviewerModel CAPI interviewer data
     */
    public function getByClientId(string $clientInterviewerId): CapiInterviewerModel
    {
        return $this->capiInterviewerService->getByClientId($clientInterviewerId);
    }

    /**
     * Get fluent resource for a specific CAPI interviewer.
     *
     * @param  string  $interviewerId  CAPI interviewer ID
     * @return CapiInterviewerResource Fluent resource for chaining operations
     */
    public function withCapiInterviewer(string $interviewerId): CapiInterviewerResource
    {
        return $this->capiInterviewerService->forInterviewer($interviewerId);
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
