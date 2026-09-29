<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaFrameEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveysQuotaFrameResponseModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * The survey's current quota frame, reached through `$survey->quota()->frame()`.
 */
class SurveyQuotaFrameService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveyQuotaFrameEndpointInterface $surveyQuotaFrameEndpoint,
    ) {}

    public function get(): SurveysQuotaFrameResponseModel
    {
        return SurveysQuotaFrameResponseModel::from(
            $this->surveyQuotaFrameEndpoint->getQuotaFrame($this->getSurveyId())
        );
    }

    /**
     * @param  array<string, mixed>|SurveyQuotaFrameRequestModel  $data
     */
    public function update(array|SurveyQuotaFrameRequestModel $data): SurveysQuotaFrameResponseModel
    {
        $payload = SurveyQuotaFrameRequestModel::from($data)->toArray();

        return SurveysQuotaFrameResponseModel::from(
            $this->surveyQuotaFrameEndpoint->setQuotaFrame($this->getSurveyId(), $payload)
        );
    }
}
