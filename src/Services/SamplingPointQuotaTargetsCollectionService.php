<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointQuotaTargetsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SamplingPointScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointQuotaTargetModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSamplingPoint;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * A sampling point's quota targets, reached through `$samplingPoint->quotaTargets()`.
 *
 * Services mirror the endpoint naming: this is the collection side of
 * SamplingPointQuotaTargetsEndpoint, and {@see SamplingPointQuotaTargetsService}
 * its {quotaLevelId} item side.
 */
class SamplingPointQuotaTargetsCollectionService implements SamplingPointScopedInterface
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

    /**
     * The target of one quota level.
     */
    public function forQuotaLevel(string $quotaLevelId): SamplingPointQuotaTargetsService
    {
        return app(SamplingPointQuotaTargetsService::class)
            ->setSurveyId($this->getSurveyId())
            ->setSamplingPointId($this->getSamplingPointId())
            ->setQuotaLevelId($quotaLevelId);
    }
}
