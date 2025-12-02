<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameEtagRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameEtagResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveysQuotaFrameResponseModel;

class SurveyQuotaService
{
    protected ?string $surveyId = null;

    public function __construct(
        protected SurveyQuotaEndpointInterface $surveyQuotaEndpoint
    ) {}

    public function setSurveyId(string $surveyId): self
    {
        $this->surveyId = $surveyId;
        return $this;
    }

    // ==============================
    // Quota Frame
    // ==============================
    public function getQuotaFrame(): SurveysQuotaFrameResponseModel
    {
        return SurveysQuotaFrameResponseModel::from($this->surveyQuotaEndpoint->getQuotaFrame($this->surveyId));
    }

    public function setQuotaFrame(SurveyQuotaFrameRequestModel $quotaFrameRequestModel): SurveysQuotaFrameResponseModel
    {
        return SurveysQuotaFrameResponseModel::from(
            $this->surveyQuotaEndpoint->setQuotaFrame($this->surveyId, $quotaFrameRequestModel->toArray())
        );
    }

    public function setQuotaLevelsTargets(string $eTag, SurveyQuotaFrameEtagRequestModel $quotaFrameEtagRequestModel): SurveyQuotaFrameEtagResponseModel
    {
        return SurveyQuotaFrameEtagResponseModel::from(
            $this->surveyQuotaEndpoint->setQuotaLevelsTargets($this->surveyId, $eTag, $quotaFrameEtagRequestModel->toArray())
        );
    }

    // ==============================
    // Quota targets
    // ==============================
    public function getQuotaTargets(): array
    {
        return $this->surveyQuotaEndpoint->getQuotaTargets($this->surveyId);
    }

    public function getQuotaTargetsByETag(int $eTag): array
    {
        return $this->surveyQuotaEndpoint->getQuotaTargetsByETag($this->surveyId, $eTag);
    }

    // ==============================
    // Quota versions
    // ==============================
    public function getQuotaVersions(): array
    {
        return $this->surveyQuotaEndpoint->getQuotaVersions($this->surveyId);
    }

    public function getQuotaVersionsByETag(int $eTag): array
    {
        return $this->surveyQuotaEndpoint->getQuotaVersionsByETag($this->surveyId, $eTag);
    }
}
