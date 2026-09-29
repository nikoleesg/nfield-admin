<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ParentSurveyCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\ParentSurvey\ParentSurveyCreateRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Resources\ParentSurveyResource;

/**
 * Parent surveys, reached through `NfieldManager::parentSurveys()`. A parent
 * survey only groups waves; each wave is a survey in its own right.
 *
 * Services mirror the endpoint naming: this pairs with
 * ParentSurveyCollectionEndpoint, and {@see ParentSurveyService} with
 * ParentSurveyEndpoint.
 */
class ParentSurveyCollectionService
{
    public function __construct(
        protected ParentSurveyCollectionEndpointInterface $parentSurveyCollectionEndpoint,
    ) {}

    /** @return Collection<int, SurveyModel> */
    public function list(): Collection
    {
        return SurveyModel::collect($this->parentSurveyCollectionEndpoint->list(), Collection::class);
    }

    /**
     * Filter and sort with OData query options.
     *
     * @param  array<string, mixed>  $filter
     * @return Collection<int, SurveyModel>
     */
    public function find(array $filter): Collection
    {
        return SurveyModel::collect($this->parentSurveyCollectionEndpoint->list($filter), Collection::class);
    }

    /**
     * Create an Online parent survey.
     *
     * @param  array<string, mixed>|ParentSurveyCreateRequestModel  $data
     */
    public function create(array|ParentSurveyCreateRequestModel $data): SurveyModel
    {
        $payload = ParentSurveyCreateRequestModel::from($data)->toArray();

        return SurveyModel::from($this->parentSurveyCollectionEndpoint->create($payload));
    }

    /**
     * One parent survey.
     */
    public function forParentSurvey(string $parentSurveyId): ParentSurveyResource
    {
        return (new ParentSurveyResource)->setParentSurveyId($parentSurveyId);
    }
}
