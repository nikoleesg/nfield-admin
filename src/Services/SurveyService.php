<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpoint as SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\FieldworkEndpoint as FieldworkEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpoint as SurveySampleEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints as EndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyBaseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;

class SurveyService
{
    public function __construct(
        private SurveyEndpointInterface                               $surveyEndpoint,
        private FieldworkEndpointInterface                            $fieldworkEndpoint,
        private SurveySampleEndpointInterface                         $surveySampleEndpoint,
        private EndpointInterface\SurveyQuotaFrameEndpointInterface   $surveyQuotaFrameEndpoint,
        private EndpointInterface\SurveyQuotaTargetsEndpointInterface $surveyQuotaTargetsEndpoint,
    ) {}

    public function for(string $surveyId): SurveyResource
    {
        return new SurveyResource(
            $this->surveyEndpoint,
            $this->fieldworkEndpoint,
            $this->surveySampleEndpoint,
            $this->surveyQuotaFrameEndpoint,
            $this->surveyQuotaTargetsEndpoint,
            $surveyId
        );
    }

    public function getSurveys(): Collection
    {
        return SurveyModel::collect($this->surveyEndpoint->all(), Collection::class);
    }

    public function querySurveys(array $filter): Collection
    {
        return SurveyModel::collect($this->surveyEndpoint->filter($filter), Collection::class);
    }

    public function createSurvey(SurveyModel $surveyModel): SurveyModel
    {
        return SurveyModel::from($this->surveyEndpoint->create($surveyModel->toArray()));
    }

    public function findSurveysByRespondent(string $value): Collection
    {
        return SurveyBaseModel::collect($this->surveyEndpoint->search($value), Collection::class);
    }
}
