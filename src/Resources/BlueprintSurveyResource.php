<?php

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyBlueprintsEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\UpdateBlueprintModel;

class BlueprintSurveyResource
{
    protected ?string $blueprintId = null;

    public function __construct(
        private readonly SurveyBlueprintsEndpointInterface $surveyBlueprintsEndpoint,
    ) {}

    public function setBlueprintId(string $blueprintId): static
    {
        $this->blueprintId = $blueprintId;

        return $this;
    }

    public function update(UpdateBlueprintModel $model): void
    {
        $this->surveyBlueprintsEndpoint->update($this->blueprintId, $model->toArray());
    }
}
