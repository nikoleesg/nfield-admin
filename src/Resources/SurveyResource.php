<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
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
use Nikoleesg\NfieldAdmin\Traits\ResolvesScopedServices;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SurveyResource implements SurveyScopedInterface
{
    use ResolvesScopedServices;
    use ScopedToSurvey;

    public function __construct(
        private readonly SurveyEndpointInterface $surveyEndpoint
    ) {}

    public function getSurvey(): SurveyModel
    {
        return SurveyModel::from($this->surveyEndpoint->get($this->getSurveyId()));
    }

    public function deleteSurvey(): void
    {
        $this->surveyEndpoint->destroy($this->getSurveyId());
    }

    public function updateSurvey(array|SurveyUpdateModel $data): SurveyModel
    {
        $payload = SurveyUpdateModel::from($data)->toArray();

        return SurveyModel::from($this->surveyEndpoint->updatePartial($this->getSurveyId(), $payload));
    }

    public function getSurveyCounts(): SurveyCountsModel
    {
        return SurveyCountsModel::from($this->surveyEndpoint->counts($this->getSurveyId()));
    }

    /** @return Collection<int, string> */
    public function getCustomColumns(): Collection
    {
        return collect($this->surveyEndpoint->getCustomColumns($this->getSurveyId()));
    }

    public function requestDataDownload(array|SurveyDataRequestModel $data): BackgroundActivityStatus
    {
        return $this->data()->downloadData($data);
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
}
