<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ParentSurveyWaveCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\ParentSurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\ParentSurvey\ParentSurveyWaveCreateRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToParentSurvey;

/**
 * A parent survey's waves, reached through `$parentSurvey->waves()`.
 *
 * Services mirror the endpoint naming: this pairs with
 * ParentSurveyWaveCollectionEndpoint; one wave is {@see SurveyWaveService}.
 */
class ParentSurveyWavesService implements ParentSurveyScopedInterface
{
    use ScopedToParentSurvey;

    public function __construct(
        protected ParentSurveyWaveCollectionEndpointInterface $parentSurveyWaveCollectionEndpoint,
    ) {}

    /** @return Collection<int, SurveyModel> */
    public function list(): Collection
    {
        return SurveyModel::collect(
            $this->parentSurveyWaveCollectionEndpoint->list($this->getParentSurveyId()),
            Collection::class
        );
    }

    /**
     * Filter and sort with OData query options.
     *
     * @param  array<string, mixed>  $filter
     * @return Collection<int, SurveyModel>
     */
    public function find(array $filter): Collection
    {
        return SurveyModel::collect(
            $this->parentSurveyWaveCollectionEndpoint->list($this->getParentSurveyId(), $filter),
            Collection::class
        );
    }

    /**
     * Create an Online wave under this parent survey.
     *
     * @param  array<string, mixed>|ParentSurveyWaveCreateRequestModel  $data
     */
    public function create(array|ParentSurveyWaveCreateRequestModel $data): SurveyModel
    {
        $payload = ParentSurveyWaveCreateRequestModel::from($data)->toArray();

        return SurveyModel::from(
            $this->parentSurveyWaveCollectionEndpoint->create($this->getParentSurveyId(), $payload)
        );
    }

    /**
     * One wave of this parent survey. Both scopes are set, so copy() works.
     */
    public function forWave(string $waveId): SurveyWaveService
    {
        return app(SurveyWaveService::class)
            ->setParentSurveyId($this->getParentSurveyId())
            ->setWaveId($waveId);
    }
}
