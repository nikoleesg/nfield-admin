<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SamplingPointScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ActivateSpareSamplingPointRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ActivateSpareSamplingPointsResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ReplaceSamplingPointWithSpareRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ReplaceSamplingPointWithSpareResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointUpdateRequestModel;
use Nikoleesg\NfieldAdmin\Resources\SamplingPointResource;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSamplingPoint;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * Operations on one sampling point, exposed through {@see SamplingPointResource}.
 *
 * Services mirror the endpoint naming: this pairs with SamplingPointEndpoint,
 * and {@see SamplingPointCollectionService} with SamplingPointCollectionEndpoint.
 */
class SamplingPointService implements SamplingPointScopedInterface
{
    use ScopedToSamplingPoint;
    use ScopedToSurvey;

    public function __construct(
        protected SamplingPointEndpointInterface $samplingPointEndpoint,
    ) {}

    public function get(): SamplingPointResponseModel
    {
        return SamplingPointResponseModel::from(
            $this->samplingPointEndpoint->get($this->getSurveyId(), $this->getSamplingPointId())
        );
    }

    /**
     * @param  array<string, mixed>|SamplingPointUpdateRequestModel  $data
     */
    public function update(array|SamplingPointUpdateRequestModel $data): SamplingPointResponseModel
    {
        $payload = SamplingPointUpdateRequestModel::from($data)->toArray();

        return SamplingPointResponseModel::from(
            $this->samplingPointEndpoint->update($this->getSurveyId(), $this->getSamplingPointId(), $payload)
        );
    }

    public function delete(): void
    {
        $this->samplingPointEndpoint->delete($this->getSurveyId(), $this->getSamplingPointId());
    }

    /**
     * @param  array<string, mixed>|ActivateSpareSamplingPointRequestModel  $data
     */
    public function activate(array|ActivateSpareSamplingPointRequestModel $data = []): ActivateSpareSamplingPointsResponseModel
    {
        $payload = ActivateSpareSamplingPointRequestModel::from($data)->toArray();

        return ActivateSpareSamplingPointsResponseModel::from(
            $this->samplingPointEndpoint->activate($this->getSurveyId(), $this->getSamplingPointId(), $payload)
        );
    }

    /**
     * @param  array<string, mixed>|ReplaceSamplingPointWithSpareRequestModel  $data
     */
    public function replace(array|ReplaceSamplingPointWithSpareRequestModel $data): ReplaceSamplingPointWithSpareResponseModel
    {
        $payload = ReplaceSamplingPointWithSpareRequestModel::from($data)->toArray();

        return ReplaceSamplingPointWithSpareResponseModel::from(
            $this->samplingPointEndpoint->replace($this->getSurveyId(), $this->getSamplingPointId(), $payload)
        );
    }
}
