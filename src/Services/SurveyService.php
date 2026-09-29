<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyBlueprintsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyBaseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyCreateModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyFromBlueprintModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Resources\BlueprintSurveyResource;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;

class SurveyService
{
    public function __construct(
        private readonly SurveyCollectionEndpointInterface $surveyCollectionEndpoint,
        private readonly SurveyBlueprintsEndpointInterface $surveyBlueprintsEndpoint,
    ) {}

    /** @return Collection<int, SurveyModel> */
    public function list(): Collection
    {
        return SurveyModel::collect($this->surveyCollectionEndpoint->list(), Collection::class);
    }

    /**
     * @param  array<string, mixed>  $filter
     * @return Collection<int, SurveyModel>
     */
    public function find(array $filter): Collection
    {
        return SurveyModel::collect($this->surveyCollectionEndpoint->find($filter), Collection::class);
    }

    /**
     * @param  array<string, mixed>|SurveyCreateModel  $data
     */
    public function create(array|SurveyCreateModel $data): SurveyModel
    {
        $payload = SurveyCreateModel::from($data)->toArray();

        return SurveyModel::from($this->surveyCollectionEndpoint->create($payload));
    }

    /**
     * @param  array<string, mixed>|SurveyFromBlueprintModel  $data
     */
    public function createFromBlueprint(array|SurveyFromBlueprintModel $data): SurveyModel
    {
        $payload = SurveyFromBlueprintModel::from($data)->toArray();

        return SurveyModel::from($this->surveyCollectionEndpoint->createFromBlueprint($payload));
    }

    /** @return Collection<int, SurveyBaseModel> */
    public function searchRespondent(string $value): Collection
    {
        return SurveyBaseModel::collect($this->surveyCollectionEndpoint->search($value), Collection::class);
    }

    public function forBlueprintSurvey(string $blueprintId): BlueprintSurveyResource
    {
        $resource = new BlueprintSurveyResource($this->surveyBlueprintsEndpoint);

        return $resource->setBlueprintId($blueprintId);
    }

    public function forSurvey(string $surveyId): SurveyResource
    {
        return (new SurveyResource)->setSurveyId($surveyId);
    }
}
