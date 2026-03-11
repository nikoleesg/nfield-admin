<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityResponseModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerResponseData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\NewCapiInterviewerRequestData;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyBaseModel;
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
    // TODO: add other (level 1) services if needed

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
     * @return Collection<SurveyModel> Collection of all surveys
     */
    public function listSurveys(): Collection
    {
        return $this->surveyService->listSurveys();
    }

    /**
     * Find surveys matching filter criteria.
     *
     * @param array $filter Filter criteria (e.g., ['SurveyName' => 'value'])
     * @return Collection<SurveyModel> Filtered collection of surveys
     */
    public function findSurveys(array $filter): Collection
    {
        return $this->surveyService->findSurveys($filter);
    }

    /**
     * Create a new survey.
     *
     * @param SurveyModel $surveyModel Survey data to create
     * @return SurveyModel Created survey with ID
     */
    public function createSurvey(SurveyModel $surveyModel): SurveyModel
    {
        return $this->surveyService->createSurvey($surveyModel);
    }

    /**
     * Search surveys by respondent criteria.
     *
     * @param string $value Search value (email, phone, ID, etc.)
     * @return Collection<SurveyBaseModel> Collection of matching surveys
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
     * @param string $activityId Background activity ID
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
     * @return Collection Collection of all CAPI interviewers
     */
    public function listCapiInterviewers(): Collection
    {
        return $this->capiInterviewerService->listCapiInterviewers();
    }

    /**
     * Find CAPI interviewers matching filter criteria.
     *
     * @param array $filter Filter criteria
     * @return Collection Filtered collection of CAPI interviewers
     */
    public function findCapiInterviewers(array $filter = []): Collection
    {
        return $this->capiInterviewerService->findCapiInterviewers($filter);
    }

    /**
     * Create a new CAPI interviewer.
     *
     * @param NewCapiInterviewerRequestData $data CAPI interviewer data
     * @return CapiInterviewerResponseData Created CAPI interviewer
     */
    public function createCapiInterviewer(NewCapiInterviewerRequestData $data): CapiInterviewerResponseData
    {
        return $this->capiInterviewerService->createCapiInterviewer($data);
    }

    /**
     * Get a CAPI interviewer by client interviewer ID.
     *
     * @param string $clientInterviewerId Client interviewer ID
     * @return CapiInterviewerData CAPI interviewer data
     */
    public function getByClientId(string $clientInterviewerId): CapiInterviewerData
    {
        return $this->capiInterviewerService->getByClientId($clientInterviewerId);
    }

    /**
     * Get fluent resource for a specific CAPI interviewer.
     *
     * @param string $interviewerId CAPI interviewer ID
     * @return CapiInterviewerResource Fluent resource for chaining operations
     */
    public function withCapiInterviewer(string $interviewerId): CapiInterviewerResource
    {
        return $this->capiInterviewerService->for($interviewerId);
    }

    public function withSurvey(string $surveyId): SurveyResource
    {
        return $this->surveyService
            ->forSurvey($surveyId);
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
