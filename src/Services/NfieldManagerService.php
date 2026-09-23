<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

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

    public function backgroundActivities(): BackgroundActivitiesService
    {
        return $this->backgroundActivitiesService;
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

    public function roles(): RoleService
    {
        return $this->roleService;
    }
}
