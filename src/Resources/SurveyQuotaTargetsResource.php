<?php

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaTargetsEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveysQuotaTargetsEtagResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveysQuotaTargetsResponseModel;

final class SurveyQuotaTargetsResource
{
    public function __construct(
        protected SurveyQuotaTargetsEndpointInterface $endpoint,
        protected string                              $surveyId,
    ) {}

    public function getQuotaTargetsWithSuccessfulCounts(int $eTag): SurveysQuotaTargetsEtagResponseModel
    {
        return SurveysQuotaTargetsEtagResponseModel::from(
            $this->endpoint->getSurveyQuotaTarget($this->surveyId, $eTag)
        );
    }

    public function getQuotaTargetsWithoutSuccessfulCounts(): SurveysQuotaTargetsResponseModel
    {
        return SurveysQuotaTargetsResponseModel::from(
            $this->endpoint->getSurveyQuotaTarget($this->surveyId)
        );
    }
}
