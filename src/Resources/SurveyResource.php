<?php

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpoint as SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\FieldworkEndpoint as FieldworkEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpoint as SurveySampleEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints as EndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyDataRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyFieldwork\SurveyFieldworkCountsResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyUpdateModel;
use Nikoleesg\NfieldAdmin\Endpoints\v2\SurveyQuotaTargetsEndpointInterface;

final class SurveyResource
{
    public function __construct(
        private SurveyEndpointInterface                               $surveyEndpoint,
        private FieldworkEndpointInterface                            $fieldworkEndpoint,
        private SurveySampleEndpointInterface                         $surveySampleEndpoint,
        private EndpointInterface\SurveyQuotaFrameEndpointInterface   $surveyQuotaFrameEndpoint,
        private EndpointInterface\SurveyQuotaTargetsEndpointInterface $surveyQuotaTargetsEndpoint,
        private string                                                $surveyId
    ) {}

    public function get(): SurveyModel
    {
        return SurveyModel::from($this->surveyEndpoint->get($this->surveyId));
    }

    public function delete(): void
    {
        $this->surveyEndpoint->destroy($this->surveyId);
    }

    public function update(SurveyUpdateModel $surveyUpdateModel): SurveyModel
    {
        return SurveyModel::from($this->surveyEndpoint->updatePartial($this->surveyId, $surveyUpdateModel->toArray()));
    }

    public function counts(): SurveyFieldworkCountsResponseModel
    {
        return SurveyFieldworkCountsResponseModel::from($this->surveyEndpoint->counts($this->surveyId));
    }

    public function getCustomColumns(): array
    {
        return $this->surveyEndpoint->getCustomColumns($this->surveyId);
    }

    public function requestDataDownload(SurveyDataRequestModel $surveyDataRequestModel): BackgroundActivityStatus
    {
        return BackgroundActivityStatus::from($this->surveyEndpoint->requestDataDownload($this->surveyId, $surveyDataRequestModel->toArray()));
    }

    public function fieldwork(): FieldworkResource
    {
        return new FieldworkResource($this->fieldworkEndpoint, $this->surveyId);
    }

    public function sample(): SurveySampleCollectionResource
    {
        return new SurveySampleCollectionResource($this->surveySampleEndpoint, $this->surveyId);
    }

    public function quotaFrame(): SurveyQuotaFrameResource
    {
        return new SurveyQuotaFrameResource($this->surveyQuotaFrameEndpoint, $this->surveyId);
    }

    public function quotaTargets(): SurveyQuotaTargetsResource
    {
        return new SurveyQuotaTargetsResource($this->surveyQuotaTargetsEndpoint, $this->surveyId);
    }
}
