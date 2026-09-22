<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ActivateSpareSamplingPointsRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ActivateSpareSamplingPointsResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointCreateRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointResponseModel;
use Nikoleesg\NfieldAdmin\Resources\SamplingPointResource;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SamplingPointService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SamplingPointCollectionEndpointInterface $samplingPointCollectionEndpoint,
        protected SamplingPointEndpointInterface $samplingPointEndpoint,
        protected SurveyEndpointInterface $surveyEndpoint,
    ) {}

    /** @return Collection<int, SamplingPointResponseModel> */
    public function listSamplingPoints(): Collection
    {
        return $this->findSamplingPoints();
    }

    /** @return Collection<int, SamplingPointResponseModel> */
    public function findSamplingPoints(array $filter = []): Collection
    {
        return SamplingPointResponseModel::collect(
            $this->samplingPointCollectionEndpoint->find($this->getSurveyId(), $filter),
            Collection::class
        );
    }

    public function createSamplingPoint(array|SamplingPointCreateRequestModel $data): SamplingPointResponseModel
    {
        $payload = SamplingPointCreateRequestModel::from($data)->toArray();

        return SamplingPointResponseModel::from(
            $this->samplingPointCollectionEndpoint->create($this->getSurveyId(), $payload)
        );
    }

    /**
     * @param  array<int, string>  $samplingPointIds
     */
    public function activateSamplingPoints(array $samplingPointIds = []): ActivateSpareSamplingPointsResponseModel
    {
        $payload = ActivateSpareSamplingPointsRequestModel::from(
            ['samplingPointIds' => $samplingPointIds]
        )->toArray();

        return ActivateSpareSamplingPointsResponseModel::from(
            $this->surveyEndpoint->batchActivateSamplingPoints($this->getSurveyId(), $payload)
        );
    }

    /**
     * Return SamplingPointResource for a specific survey samplingPoint
     */
    public function forSamplingPoint(string $samplingPointId): SamplingPointResource
    {
        return (new SamplingPointResource($this->samplingPointEndpoint))
            ->setSurveyId($this->getSurveyId())
            ->setSamplingPointId($samplingPointId);
    }
}
