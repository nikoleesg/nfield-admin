<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointQuotaTargetsEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointQuotaLevelTargetModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointQuotaTargetModel;

class SamplingPointQuotaTargetsService
{
    public function __construct(
        protected SamplingPointQuotaTargetsEndpointInterface $samplingPointQuotaTargetsEndpoint,
        protected readonly string $surveyId,
        protected readonly string $samplingPointId,
    ) {}

    /** @return Collection<int, SamplingPointQuotaTargetModel> */
    public function listQuotaTargets(): Collection
    {
        return SamplingPointQuotaTargetModel::collect(
            $this->samplingPointQuotaTargetsEndpoint->list($this->surveyId, $this->samplingPointId),
            Collection::class
        );
    }

    public function getQuotaTargets(string $quotaLevelId): SamplingPointQuotaTargetModel
    {
        return SamplingPointQuotaTargetModel::from(
            $this->samplingPointQuotaTargetsEndpoint->get($this->surveyId, $this->samplingPointId, $quotaLevelId)
        );
    }

    public function setQuotaTargets(string $quotaLevelId, array|SamplingPointQuotaLevelTargetModel $data): SamplingPointQuotaTargetModel
    {
        $payload = SamplingPointQuotaLevelTargetModel::from($data)->toArray();

        return SamplingPointQuotaTargetModel::from(
            $this->samplingPointQuotaTargetsEndpoint->update($this->surveyId, $this->samplingPointId, $quotaLevelId, $payload)
        );
    }
}
