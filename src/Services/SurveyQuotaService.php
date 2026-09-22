<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaFrameEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaTargetsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaVersionsEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameEtagRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameEtagResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveysQuotaFrameResponseModel;

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

    public function setQuotaFrame(SurveyQuotaFrameRequestModel $quotaFrameRequestModel): SurveysQuotaFrameResponseModel
    {
        return SurveysQuotaFrameResponseModel::from(
            $this->surveyQuotaFrameEndpoint->setQuotaFrame($this->surveyId, $quotaFrameRequestModel->toArray())
        );
    }

    public function setQuotaLevelsTargets(string $eTag, SurveyQuotaFrameEtagRequestModel $quotaFrameEtagRequestModel): SurveyQuotaFrameEtagResponseModel
    {
        return SurveyQuotaFrameEtagResponseModel::from(
            $this->surveyQuotaFrameEndpoint->setQuotaLevelsTargets($this->surveyId, $eTag, $quotaFrameEtagRequestModel->toArray())
        );
    }

    // ==============================
    // Quota targets
    // ==============================
    public function getQuotaTargets(): array
    {
        return $this->surveyQuotaTargetsEndpoint->getQuotaTargets($this->surveyId);
    }

    public function getQuotaTargetsByETag(int $eTag): array
    {
        return $this->surveyQuotaTargetsEndpoint->getQuotaTargetsByETag($this->surveyId, $eTag);
    }

    // ==============================
    // Quota versions
    // ==============================
    public function getQuotaVersions(): array
    {
        return $this->surveyQuotaVersionsEndpoint->getQuotaVersions($this->surveyId);
    }

    public function getQuotaVersionsByETag(int $eTag): array
    {
        return $this->surveyQuotaVersionsEndpoint->getQuotaVersionsByETag($this->surveyId, $eTag);
    }
}
