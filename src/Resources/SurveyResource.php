<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyCountsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyDataRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyMoveModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyUpdateModel;
use Nikoleesg\NfieldAdmin\Services\SamplingPointCollectionService;
use Nikoleesg\NfieldAdmin\Services\SurveyAssignmentService;
use Nikoleesg\NfieldAdmin\Services\SurveyDataRetentionSettingsService;
use Nikoleesg\NfieldAdmin\Services\SurveyDataService;
use Nikoleesg\NfieldAdmin\Services\SurveyFieldworkService;
use Nikoleesg\NfieldAdmin\Services\SurveyGeneralSettingsService;
use Nikoleesg\NfieldAdmin\Services\SurveyInterviewQualityCollectionService;
use Nikoleesg\NfieldAdmin\Services\SurveyPackageService;
use Nikoleesg\NfieldAdmin\Services\SurveyPerformanceService;
use Nikoleesg\NfieldAdmin\Services\SurveyPublicIdsService;
use Nikoleesg\NfieldAdmin\Services\SurveyPublishService;
use Nikoleesg\NfieldAdmin\Services\SurveyResponseCodeCollectionService;
use Nikoleesg\NfieldAdmin\Services\SurveySampleCollectionService;
use Nikoleesg\NfieldAdmin\Services\SurveySamplingMethodService;
use Nikoleesg\NfieldAdmin\Services\SurveyScriptService;
use Nikoleesg\NfieldAdmin\Services\SurveyService;
use Nikoleesg\NfieldAdmin\Services\SurveySettingsService;
use Nikoleesg\NfieldAdmin\Services\SurveyVarFileService;
use Nikoleesg\NfieldAdmin\Services\SurveyVersionsService;
use Nikoleesg\NfieldAdmin\Traits\ResolvesScopedServices;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SurveyResource implements SurveyScopedInterface
{
    use ResolvesScopedServices;
    use ScopedToSurvey;

    public function get(): SurveyModel
    {
        return $this->item()->get();
    }

    /**
     * @param  array<string, mixed>|SurveyUpdateModel  $data
     */
    public function update(array|SurveyUpdateModel $data): SurveyModel
    {
        return $this->item()->update($data);
    }

    public function delete(): void
    {
        $this->item()->delete();
    }

    public function counts(): SurveyCountsModel
    {
        return $this->item()->counts();
    }

    /** @return Collection<int, string> */
    public function customColumns(): Collection
    {
        return $this->item()->customColumns();
    }

    public function moveToGroup(int $surveyGroupId): SurveyMoveModel
    {
        return $this->item()->moveToGroup($surveyGroupId);
    }

    /**
     * Shortcut for `data()->download()`.
     *
     * @param  array<string, mixed>|SurveyDataRequestModel  $data
     */
    public function requestDownload(array|SurveyDataRequestModel $data): BackgroundActivityStatus
    {
        return $this->data()->download($data);
    }

    public function samplingPoints(): SamplingPointCollectionService
    {
        return $this->resolveService(SamplingPointCollectionService::class);
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

    public function samples(): SurveySampleCollectionService
    {
        return $this->resolveService(SurveySampleCollectionService::class);
    }

    public function samplingMethod(): SurveySamplingMethodService
    {
        return $this->resolveService(SurveySamplingMethodService::class);
    }

    public function quota(): SurveyQuotaResource
    {
        return $this->resolveService(SurveyQuotaResource::class);
    }

    public function settings(): SurveySettingsService
    {
        return $this->resolveService(SurveySettingsService::class);
    }

    public function generalSettings(): SurveyGeneralSettingsService
    {
        return $this->resolveService(SurveyGeneralSettingsService::class);
    }

    public function publish(): SurveyPublishService
    {
        return $this->resolveService(SurveyPublishService::class);
    }

    public function publicIds(): SurveyPublicIdsService
    {
        return $this->resolveService(SurveyPublicIdsService::class);
    }

    public function interviewQuality(): SurveyInterviewQualityCollectionService
    {
        return $this->resolveService(SurveyInterviewQualityCollectionService::class);
    }

    public function performance(): SurveyPerformanceService
    {
        return $this->resolveService(SurveyPerformanceService::class);
    }

    public function responseCodes(): SurveyResponseCodeCollectionService
    {
        return $this->resolveService(SurveyResponseCodeCollectionService::class);
    }

    public function dataRetentionSettings(): SurveyDataRetentionSettingsService
    {
        return $this->resolveService(SurveyDataRetentionSettingsService::class);
    }

    public function versions(): SurveyVersionsService
    {
        return $this->resolveService(SurveyVersionsService::class);
    }

    public function package(): SurveyPackageService
    {
        return $this->resolveService(SurveyPackageService::class);
    }

    public function script(): SurveyScriptService
    {
        return $this->resolveService(SurveyScriptService::class);
    }

    public function varFile(): SurveyVarFileService
    {
        return $this->resolveService(SurveyVarFileService::class);
    }

    private function item(): SurveyService
    {
        return $this->resolveService(SurveyService::class);
    }
}
