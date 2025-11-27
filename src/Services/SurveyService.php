<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyBaseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;

class SurveyService
{
    public function __construct(
        private readonly SurveyCollectionEndpointInterface $surveyCollectionEndpoint,
        private readonly SurveyEndpointInterface $surveyEndpoint,
    ) {}

    public function listSurveys(): Collection
    {
        return SurveyModel::collect($this->surveyCollectionEndpoint->list(), Collection::class);
    }

    public function findSurveys(array $filter): Collection
    {
        return SurveyModel::collect($this->surveyCollectionEndpoint->find($filter), Collection::class);
    }

    public function createSurvey(SurveyModel $surveyModel): SurveyModel
    {
        return SurveyModel::from($this->surveyCollectionEndpoint->create($surveyModel->toArray()));
    }

    public function findSurveysByRespondent(string $value): Collection
    {
        return SurveyBaseModel::collect($this->surveyCollectionEndpoint->search($value), Collection::class);
    }

    public function for(string $surveyId): SurveyResource
    {
        $surveyResource = new SurveyResource($this->surveyEndpoint);

        return $surveyResource->setSurveyId($surveyId);
    }

    public function forSurvey(string $surveyId): SurveyResource
    {
        return $this->for($surveyId);
    }
}
