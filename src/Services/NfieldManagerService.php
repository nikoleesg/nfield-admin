<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityResponseModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerResponseModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\NewCapiInterviewerRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyBaseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyCreateModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyFromBlueprintModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Resources\BlueprintSurveyResource;
use Nikoleesg\NfieldAdmin\Resources\CapiInterviewerResource;
use Nikoleesg\NfieldAdmin\Resources\SamplingPointResource;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;

/**
 * NfieldManagerService - Main entry point for Nfield Admin SDK
 *
 * Provides fluent interface for managing surveys, sampling points, fieldwork,
 * quotas, CAPI interviewers, and background activities via the Nfield API v2.
 *
 * Usage:
 * ```
 * $manager = app(NfieldManagerService::class);
 * $surveys = $manager->listSurveys();
 * $manager->withSurvey('survey-id')->fieldwork()->start();
 * $interviewers = $manager->listCapiInterviewers();
 * ```
 */
class NfieldManagerService
{
    public function __construct(
        protected SurveyService $surveyService,
        protected CapiInterviewerService $capiInterviewerService,
        protected BackgroundActivitiesService $backgroundActivitiesService,
    ) {}

    // ========================================
    // Explicit Method Declarations
    // ========================================

    /**
     * List all surveys from the API.
     *
     * @return Collection<int, SurveyModel> Collection of all surveys
     */
    public function listSurveys(): Collection
    {
        return $this->surveyService->listSurveys();
    }

    /**
     * Find surveys matching filter criteria.
     *
     * @param  array  $filter  Filter criteria (e.g., ['SurveyName' => 'value'])
     * @return Collection<int, SurveyModel> Filtered collection of surveys
     */
    public function findSurveys(array $filter): Collection
    {
        return $this->surveyService->findSurveys($filter);
    }

    /**
     * Creates a new survey based on the provided data.
     *
     * @param  array|SurveyCreateModel  $data  Data for the new survey
     * @return SurveyModel Created survey with ID
     */
    public function createSurvey(array|SurveyCreateModel $data): SurveyModel
    {
        return $this->surveyService->createSurvey($data);
    }

    /**
     * Create a new survey from a blueprint survey.
     *
     * @param  array|SurveyFromBlueprintModel  $data  Blueprint model with survey name and blueprint ID
     * @return SurveyModel Created survey with all blueprint configurations copied
     */
    public function createSurveyFromBlueprint(array|SurveyFromBlueprintModel $data): SurveyModel
    {
        return $this->surveyService->createSurveyFromBlueprint($data);
    }

    /**
     * Search surveys by respondent criteria.
     *
     * @param  string  $value  Search value (email, phone, ID, etc.)
     * @return Collection<int, SurveyBaseModel> Collection of matching surveys
     */
    public function searchRespondent(string $value): Collection
    {
        return $this->surveyService->findSurveysByRespondent($value);
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

    public function withSurvey(string $surveyId): SurveyResource
    {
        return $this->surveyService->forSurvey($surveyId);
    }

    public function withBlueprintSurvey(string $blueprintId): BlueprintSurveyResource
    {
        return $this->surveyService->forBlueprintSurvey($blueprintId);
    }

    public function withSurveySamplingPoint(string $surveyId, string $samplingPoint): SamplingPointResource
    {
        return $this
            ->surveyService
            ->forSurvey($surveyId)
            ->samplingPoints()
            ->forSamplingPoint($samplingPoint);
    }
}
