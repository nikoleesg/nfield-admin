<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointQuotaTargetsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SamplingPointScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointQuotaLevelTargetModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointQuotaLevelTargetUpdateRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointQuotaTargetModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSamplingPoint;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SamplingPointQuotaTargetsService implements SamplingPointScopedInterface
{
    use ScopedToSamplingPoint;
    use ScopedToSurvey;

    public function __construct(
        protected SamplingPointQuotaTargetsEndpointInterface $samplingPointQuotaTargetsEndpoint,
    ) {}

    /** @return Collection<int, SamplingPointQuotaTargetModel> */
    public function list(): Collection
    {
        return SamplingPointQuotaTargetModel::collect(
            $this->samplingPointQuotaTargetsEndpoint->list($this->getSurveyId(), $this->getSamplingPointId()),
            Collection::class
        );
    }

    public function get(string $quotaLevelId): SamplingPointQuotaTargetModel
    {
        return SamplingPointQuotaTargetModel::from(
            $this->samplingPointQuotaTargetsEndpoint->get($this->getSurveyId(), $this->getSamplingPointId(), $quotaLevelId)
        );
    }

    /**
     * @param  array<string, mixed>|SamplingPointQuotaLevelTargetUpdateRequestModel|SamplingPointQuotaLevelTargetModel  $data
     */
    public function update(string $quotaLevelId, array|SamplingPointQuotaLevelTargetUpdateRequestModel|SamplingPointQuotaLevelTargetModel $data): SamplingPointQuotaTargetModel
    {
        $payload = SamplingPointQuotaLevelTargetUpdateRequestModel::from($data)->toArray();

        return SamplingPointQuotaTargetModel::from(
            $this->samplingPointQuotaTargetsEndpoint->update($this->getSurveyId(), $this->getSamplingPointId(), $quotaLevelId, $payload)
        );
    }
}
