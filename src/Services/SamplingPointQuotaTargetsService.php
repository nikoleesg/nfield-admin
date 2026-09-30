<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointQuotaTargetsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\QuotaLevelScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointQuotaLevelTargetModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointQuotaLevelTargetUpdateRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointQuotaTargetModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToQuotaLevel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSamplingPoint;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * The target of one quota level of a sampling point, reached through
 * `$samplingPoint->quotaTargets()->forQuotaLevel($quotaLevelId)`.
 *
 * Services mirror the endpoint naming: this is the {quotaLevelId} item side of
 * SamplingPointQuotaTargetsEndpoint, and
 * {@see SamplingPointQuotaTargetsCollectionService} its collection side.
 */
class SamplingPointQuotaTargetsService implements QuotaLevelScopedInterface
{
    use ScopedToQuotaLevel;
    use ScopedToSamplingPoint;
    use ScopedToSurvey;

    public function __construct(
        protected SamplingPointQuotaTargetsEndpointInterface $samplingPointQuotaTargetsEndpoint,
    ) {}

    public function get(): SamplingPointQuotaTargetModel
    {
        return SamplingPointQuotaTargetModel::from(
            $this->samplingPointQuotaTargetsEndpoint->get($this->getSurveyId(), $this->getSamplingPointId(), $this->getQuotaLevelId())
        );
    }

    /**
     * @param  array<string, mixed>|SamplingPointQuotaLevelTargetUpdateRequestModel|SamplingPointQuotaLevelTargetModel  $data
     */
    public function update(array|SamplingPointQuotaLevelTargetUpdateRequestModel|SamplingPointQuotaLevelTargetModel $data): SamplingPointQuotaTargetModel
    {
        $payload = SamplingPointQuotaLevelTargetUpdateRequestModel::from($data)->toArray();

        return SamplingPointQuotaTargetModel::from(
            $this->samplingPointQuotaTargetsEndpoint->update($this->getSurveyId(), $this->getSamplingPointId(), $this->getQuotaLevelId(), $payload)
        );
    }
}
