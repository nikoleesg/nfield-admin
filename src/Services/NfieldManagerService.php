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
        protected SurveyCollectionService $surveyCollectionService,
        protected CapiInterviewerCollectionService $capiInterviewerCollectionService,
        protected BackgroundActivitiesService $backgroundActivitiesService,
        protected RoleService $roleService,
        protected EventSubscriptionCollectionService $eventSubscriptionCollectionService,
        protected InterviewersWorklogService $interviewersWorklogService,
        protected SurveyResourceUsageService $surveyResourceUsageService,
        protected ResponseCodeCollectionService $responseCodeCollectionService,
        protected SurveyGroupCollectionService $surveyGroupCollectionService,
        protected RequestConfigurationCollectionService $requestConfigurationCollectionService,
        protected ThemeCollectionService $themeCollectionService,
    ) {}

    // ========================================
    // Explicit Method Declarations
    // ========================================

    public function surveys(): SurveyCollectionService
    {
        return $this->surveyCollectionService;
    }

    public function surveyGroups(): SurveyGroupCollectionService
    {
        return $this->surveyGroupCollectionService;
    }

    public function surveyResourceUsage(): SurveyResourceUsageService
    {
        return $this->surveyResourceUsageService;
    }

    public function requestConfigurations(): RequestConfigurationCollectionService
    {
        return $this->requestConfigurationCollectionService;
    }

    public function themes(): ThemeCollectionService
    {
        return $this->themeCollectionService;
    }

    public function responseCodes(): ResponseCodeCollectionService
    {
        return $this->responseCodeCollectionService;
    }

    public function backgroundActivities(): BackgroundActivitiesService
    {
        return $this->backgroundActivitiesService;
    }

    // ========================================
    // CAPI Interviewer Methods
    // ========================================

    public function capiInterviewers(): CapiInterviewerCollectionService
    {
        return $this->capiInterviewerCollectionService;
    }

    public function interviewersWorklog(): InterviewersWorklogService
    {
        return $this->interviewersWorklogService;
    }

    // ========================================
    // Event Subscriptions
    // ========================================

    public function eventSubscriptions(): EventSubscriptionCollectionService
    {
        return $this->eventSubscriptionCollectionService;
    }

    // ========================================
    // Role Methods
    // ========================================

    public function roles(): RoleService
    {
        return $this->roleService;
    }
}
