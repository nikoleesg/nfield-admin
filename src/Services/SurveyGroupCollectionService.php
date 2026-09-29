<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGroupCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGroupModel;

/**
 * The domain's survey groups, reached through `NfieldManager::surveyGroups()`.
 *
 * Services mirror the endpoint naming: this pairs with
 * SurveyGroupCollectionEndpoint, and {@see SurveyGroupService} with
 * SurveyGroupEndpoint.
 */
class SurveyGroupCollectionService
{
    public function __construct(
        protected SurveyGroupCollectionEndpointInterface $surveyGroupCollectionEndpoint,
    ) {}

    /** @return Collection<int, SurveyGroupModel> */
    public function list(): Collection
    {
        return SurveyGroupModel::collect($this->surveyGroupCollectionEndpoint->list(), Collection::class);
    }

    /**
     * One survey group.
     */
    public function forSurveyGroup(int $surveyGroupId): SurveyGroupService
    {
        return app(SurveyGroupService::class)->setSurveyGroupId($surveyGroupId);
    }
}
