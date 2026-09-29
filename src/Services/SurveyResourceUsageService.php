<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyResourceUsageEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Domain\SurveyResources\SurveyResourceUsageModel;

/**
 * Each survey's resource usage, reached through `NfieldManager::surveyResourceUsage()`.
 *
 * Services mirror the endpoint naming: this pairs with SurveyResourceUsageEndpoint
 * (`/v2/surveyResources` in the API).
 */
class SurveyResourceUsageService
{
    public function __construct(
        protected SurveyResourceUsageEndpointInterface $surveyResourceUsageEndpoint,
    ) {}

    /** @return Collection<int, SurveyResourceUsageModel> */
    public function list(): Collection
    {
        return SurveyResourceUsageModel::collect($this->surveyResourceUsageEndpoint->list(), Collection::class);
    }

    /**
     * Filter and sort with OData query options, e.g. `['$filter' => "State eq 1"]`.
     *
     * @param  array<string, mixed>  $filter
     * @return Collection<int, SurveyResourceUsageModel>
     */
    public function find(array $filter): Collection
    {
        return SurveyResourceUsageModel::collect($this->surveyResourceUsageEndpoint->find($filter), Collection::class);
    }
}
