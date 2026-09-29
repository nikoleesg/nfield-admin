<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyBlueprintsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\BlueprintScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\UpdateBlueprintModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToBlueprint;

/**
 * One blueprint survey, reached through
 * `NfieldManager::surveys()->forBlueprintSurvey($blueprintId)`.
 *
 * Services mirror the endpoint naming: this pairs with SurveyBlueprintsEndpoint.
 */
class SurveyBlueprintService implements BlueprintScopedInterface
{
    use ScopedToBlueprint;

    public function __construct(
        protected SurveyBlueprintsEndpointInterface $surveyBlueprintsEndpoint,
    ) {}

    /**
     * @param  array<string, mixed>|UpdateBlueprintModel  $data
     */
    public function update(array|UpdateBlueprintModel $data): void
    {
        $payload = UpdateBlueprintModel::from($data)->toArray();

        $this->surveyBlueprintsEndpoint->update($this->getBlueprintId(), $payload);
    }
}
