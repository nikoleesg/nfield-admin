<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyBlueprintsEndpointInterface
{
    /**
     * Updates an existing blueprint by copying configuration from a survey.
     */
    public function update(string $blueprintId, array $updateBlueprintModel): void;
}
