<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointQuotaTargetsEndpointInterface;

class SamplingPointQuotaTargetsService
{
    protected ?string $surveyId = null;
    protected ?string $samplingPointId = null;

    public function __construct(
        protected SamplingPointQuotaTargetsEndpointInterface $samplingPointQuotaTargetsEndpoint
    ) {}

    public function setSurveyId(string $surveyId): self
    {
        $this->surveyId = $surveyId;
        return $this;
    }

    public function setSamplingPointId(string $samplingPointId): self
    {
        $this->samplingPointId = $samplingPointId;
        return $this;
    }

    public function listQuotaTargets(): array
    {
        return $this->samplingPointQuotaTargetsEndpoint->list($this->surveyId, $this->samplingPointId);
    }

    public function getQuotaTargets(string $quotaLevelId): array
    {
        return $this->samplingPointQuotaTargetsEndpoint->get($this->surveyId, $this->samplingPointId, $quotaLevelId);
    }

    public function setQuotaTargets(string $quotaLevelId, array $samplingPointQuotaLevelTargetModel): array
    {
        return $this->samplingPointQuotaTargetsEndpoint->update($this->surveyId, $this->samplingPointId, $quotaLevelId, $samplingPointQuotaLevelTargetModel);
    }
}
