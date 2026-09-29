<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ActivateSpareSamplingPointsRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ActivateSpareSamplingPointsResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointCreateRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointResponseModel;
use Nikoleesg\NfieldAdmin\Resources\SamplingPointResource;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * A survey's sampling points, reached through `$survey->samplingPoints()`.
 *
 * Services mirror the endpoint naming: this pairs with
 * SamplingPointCollectionEndpoint, and {@see SamplingPointService} with
 * SamplingPointEndpoint.
 */
class SamplingPointCollectionService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SamplingPointCollectionEndpointInterface $samplingPointCollectionEndpoint,
        protected SurveyEndpointInterface $surveyEndpoint,
    ) {}

    /** @return Collection<int, SamplingPointResponseModel> */
    public function list(): Collection
    {
        return $this->find();
    }

    /**
     * @param  array<string, mixed>  $filter
     * @return Collection<int, SamplingPointResponseModel>
     */
    public function find(array $filter = []): Collection
    {
        return SamplingPointResponseModel::collect(
            $this->samplingPointCollectionEndpoint->find($this->getSurveyId(), $filter),
            Collection::class
        );
    }

    /**
     * @param  array<string, mixed>|SamplingPointCreateRequestModel  $data
     */
    public function create(array|SamplingPointCreateRequestModel $data): SamplingPointResponseModel
    {
        $payload = SamplingPointCreateRequestModel::from($data)->toArray();

        return SamplingPointResponseModel::from(
            $this->samplingPointCollectionEndpoint->create($this->getSurveyId(), $payload)
        );
    }

    /**
     * Activate several spare sampling points at once.
     *
     * @param  array<int, string>  $samplingPointIds
     */
    public function activate(array $samplingPointIds = []): ActivateSpareSamplingPointsResponseModel
    {
        $payload = ActivateSpareSamplingPointsRequestModel::from(
            ['samplingPointIds' => $samplingPointIds]
        )->toArray();

        return ActivateSpareSamplingPointsResponseModel::from(
            $this->surveyEndpoint->batchActivateSamplingPoints($this->getSurveyId(), $payload)
        );
    }

    /**
     * One sampling point of this survey.
     */
    public function forSamplingPoint(string $samplingPointId): SamplingPointResource
    {
        return (new SamplingPointResource)
            ->setSurveyId($this->getSurveyId())
            ->setSamplingPointId($samplingPointId);
    }
}
