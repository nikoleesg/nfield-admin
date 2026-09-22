<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaFrameEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaTargetsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaVersionsEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\Quota\QuotaFrameModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Quota\QuotaFrameVersionModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameEtagRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameEtagResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveysQuotaFrameResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveysQuotaTargetsEtagResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveysQuotaTargetsResponseModel;

class SurveyQuotaService
{
    public function __construct(
        protected SurveyQuotaFrameEndpointInterface $surveyQuotaFrameEndpoint,
        protected SurveyQuotaTargetsEndpointInterface $surveyQuotaTargetsEndpoint,
        protected SurveyQuotaVersionsEndpointInterface $surveyQuotaVersionsEndpoint,
        protected readonly string $surveyId,
    ) {}

    // ==============================
    // Quota Frame
    // ==============================
    public function getQuotaFrame(): SurveysQuotaFrameResponseModel
    {
        return SurveysQuotaFrameResponseModel::from($this->surveyQuotaFrameEndpoint->getQuotaFrame($this->surveyId));
    }

    public function setQuotaFrame(array|SurveyQuotaFrameRequestModel $data): SurveysQuotaFrameResponseModel
    {
        $payload = SurveyQuotaFrameRequestModel::from($data)->toArray();

        return SurveysQuotaFrameResponseModel::from(
            $this->surveyQuotaFrameEndpoint->setQuotaFrame($this->surveyId, $payload)
        );
    }

    public function setQuotaLevelsTargets(string $eTag, array|SurveyQuotaFrameEtagRequestModel $data): SurveyQuotaFrameEtagResponseModel
    {
        $payload = SurveyQuotaFrameEtagRequestModel::from($data)->toArray();

        return SurveyQuotaFrameEtagResponseModel::from(
            $this->surveyQuotaFrameEndpoint->setQuotaLevelsTargets($this->surveyId, $eTag, $payload)
        );
    }

    // ==============================
    // Quota targets
    // ==============================
    public function getQuotaTargets(): SurveysQuotaTargetsResponseModel
    {
        return SurveysQuotaTargetsResponseModel::from(
            $this->surveyQuotaTargetsEndpoint->getQuotaTargets($this->surveyId)
        );
    }

    public function getQuotaTargetsByETag(int $eTag): SurveysQuotaTargetsEtagResponseModel
    {
        return SurveysQuotaTargetsEtagResponseModel::from(
            $this->surveyQuotaTargetsEndpoint->getQuotaTargetsByETag($this->surveyId, $eTag)
        );
    }

    // ==============================
    // Quota versions
    // ==============================
    /** @return Collection<int, QuotaFrameVersionModel> */
    public function getQuotaVersions(): Collection
    {
        return QuotaFrameVersionModel::collect(
            $this->surveyQuotaVersionsEndpoint->getQuotaVersions($this->surveyId),
            Collection::class
        );
    }

    public function getQuotaVersionsByETag(int $eTag): QuotaFrameModel
    {
        return QuotaFrameModel::from(
            $this->surveyQuotaVersionsEndpoint->getQuotaVersionsByETag($this->surveyId, $eTag)
        );
    }
}
