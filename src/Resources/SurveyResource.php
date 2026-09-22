<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Illuminate\Foundation\Application;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyCountsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyDataRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyUpdateModel;
use Nikoleesg\NfieldAdmin\Services\SamplingPointService;
use Nikoleesg\NfieldAdmin\Services\SurveyAssignmentService;
use Nikoleesg\NfieldAdmin\Services\SurveyDataService;
use Nikoleesg\NfieldAdmin\Services\SurveyFieldworkService;
use Nikoleesg\NfieldAdmin\Services\SurveyPublicIdsService;
use Nikoleesg\NfieldAdmin\Services\SurveyPublishService;
use Nikoleesg\NfieldAdmin\Services\SurveyQuotaService;
use Nikoleesg\NfieldAdmin\Services\SurveySampleService;
use Nikoleesg\NfieldAdmin\Services\SurveySamplingMethodService;
use Nikoleesg\NfieldAdmin\Services\SurveySettingsService;

class SurveyResource
{
    protected ?string $surveyId = null;

    protected ?SamplingPointService $samplingPointService = null;

    protected ?SurveyAssignmentService $surveyAssignmentService = null;

    protected ?SurveyFieldworkService $surveyFieldworkService = null;

    protected ?SurveyDataService $surveyDataService = null;

    protected ?SurveySampleService $surveySampleService = null;

    protected ?SurveySamplingMethodService $surveySamplingMethodService = null;

    protected ?SurveyQuotaService $surveyQuotaService = null;

    protected ?SurveySettingsService $surveySettingsService = null;

    protected ?SurveyPublishService $surveyPublishService = null;

    protected ?SurveyPublicIdsService $surveyPublicIdsService = null;

    public function __construct(
        private readonly SurveyEndpointInterface $surveyEndpoint
    ) {}

    public function setSurveyId(string $surveyId): static
    {
        $this->surveyId = $surveyId;

        return $this;
    }

    public function getSurvey(): SurveyModel
    {
        return SurveyModel::from($this->surveyEndpoint->get($this->surveyId));
    }

    public function deleteSurvey(): void
    {
        $this->surveyEndpoint->destroy($this->surveyId);
    }

    public function updateSurvey(SurveyUpdateModel $surveyUpdateModel): SurveyModel
    {
        return SurveyModel::from($this->surveyEndpoint->updatePartial($this->surveyId, $surveyUpdateModel->toArray()));
    }

    public function getSurveyCounts(): SurveyCountsModel
    {
        return SurveyCountsModel::from($this->surveyEndpoint->counts($this->surveyId));
    }

    public function getCustomColumns(): array
    {
        return $this->surveyEndpoint->getCustomColumns($this->surveyId);
    }

    public function requestDataDownload(SurveyDataRequestModel $surveyDataRequestModel): BackgroundActivityStatus
    {
        return $this->data()->downloadData($surveyDataRequestModel);
    }

    public function samplingPoints(): SamplingPointService
    {
        return $this->resolveService(SamplingPointService::class);
    }

    public function assignments(): SurveyAssignmentService
    {
        return $this->resolveService(SurveyAssignmentService::class);
    }

    public function fieldwork(): SurveyFieldworkService
    {
        return $this->resolveService(SurveyFieldworkService::class);
    }

    public function data(): SurveyDataService
    {
        return $this->resolveService(SurveyDataService::class);
    }

    public function samples(): SurveySampleService
    {
        return $this->resolveService(SurveySampleService::class);
    }

    public function samplingMethod(): SurveySamplingMethodService
    {
        return $this->resolveService(SurveySamplingMethodService::class);
    }

    public function quota(): SurveyQuotaService
    {
        return $this->resolveService(SurveyQuotaService::class);
    }

    public function settings(): SurveySettingsService
    {
        return $this->resolveService(SurveySettingsService::class);
    }

    public function publish(): SurveyPublishService
    {
        return $this->resolveService(SurveyPublishService::class);
    }

    public function publicIds(): SurveyPublicIdsService
    {
        return $this->resolveService(SurveyPublicIdsService::class);
    }

    /**
     * Helper function to resolve service
     *
     * @return Application|mixed|object|string
     */
    protected function resolveService(string $serviceClass): mixed
    {
        // Lazy load with property caching
        $property = lcfirst(class_basename($serviceClass));

        $service = $this->$property ??= app($serviceClass);

        if ($this->surveyId !== null) {
            $service->setSurveyId($this->surveyId);
        }

        return $service;
    }

    //
    //    public function quotaFrame(): SurveyQuotaFrameResource
    //    {
    //        return new SurveyQuotaFrameResource($this->surveyQuotaFrameEndpoint, $this->surveyId);
    //    }
    //
    //    public function quotaTargets(): SurveyQuotaTargetsResource
    //    {
    //        return new SurveyQuotaTargetsResource($this->surveyQuotaTargetsEndpoint, $this->surveyId);
    //    }
    //

}
