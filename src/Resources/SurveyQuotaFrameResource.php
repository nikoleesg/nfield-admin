<?php

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaFrameEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveysQuotaFrameResponseModel;

final class SurveyQuotaFrameResource
{
    public function __construct(
        protected SurveyQuotaFrameEndpointInterface $endpoint,
        protected string $surveyId,
    ) {}

    public function getSurveyQuotaFrame(): SurveysQuotaFrameResponseModel
    {
        return SurveysQuotaFrameResponseModel::from($this->endpoint->getSurveyQuotaFrame($this->surveyId));
    }

    public function getSurveyQuotaETag(): string
    {
        return $this->getSurveyQuotaFrame()->quotaETag;
    }
}
