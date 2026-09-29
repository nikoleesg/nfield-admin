<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyBlueprintsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\BlueprintScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\UpdateBlueprintModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToBlueprint;

class BlueprintSurveyResource implements BlueprintScopedInterface
{
    use ScopedToBlueprint;

    public function __construct(
        private readonly SurveyBlueprintsEndpointInterface $surveyBlueprintsEndpoint,
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
