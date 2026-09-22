<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ActivateSpareSamplingPointsRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ActivateSpareSamplingPointsResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointCreateRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointResponseModel;
use Nikoleesg\NfieldAdmin\Resources\SamplingPointResource;

class SamplingPointService
{
    public function __construct(
        protected SamplingPointCollectionEndpointInterface $samplingPointCollectionEndpoint,
        protected SamplingPointEndpointInterface $samplingPointEndpoint,
        protected SurveyEndpointInterface $surveyEndpoint,
        protected readonly string $surveyId,
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
            $this->samplingPointCollectionEndpoint->find($this->surveyId, $filter),
            Collection::class
        );
    }

    public function createSamplingPoint(array|SamplingPointCreateRequestModel $data): SamplingPointResponseModel
    {
        $payload = SamplingPointCreateRequestModel::from($data)->toArray();

        return SamplingPointResponseModel::from(
            $this->samplingPointCollectionEndpoint->create($this->surveyId, $payload)
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
            $this->surveyEndpoint->batchActivateSamplingPoints($this->surveyId, $payload)
        );
    }

    /**
     * Return SamplingPointResource for a specific survey samplingPoint
     */
    public function for(string $samplingPointId): SamplingPointResource
    {
        return (new SamplingPointResource($this->samplingPointEndpoint))
            ->setSurveyId($this->surveyId)
            ->setSamplingPointId($samplingPointId);
    }

    public function forSamplingPoint(string $samplingPointId): SamplingPointResource
    {
        return $this->for($samplingPointId);
    }
}
