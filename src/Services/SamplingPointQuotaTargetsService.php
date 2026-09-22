<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointQuotaTargetsEndpointInterface;

class SamplingPointQuotaTargetsService
{
    public function __construct(
        protected SamplingPointQuotaTargetsEndpointInterface $samplingPointQuotaTargetsEndpoint,
        protected readonly string $surveyId,
        protected readonly string $samplingPointId,
    ) {}

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
