<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyMoveEndpointInterface
{
    /**
     * Move a survey to another survey group.
     *
     * @param  array<string, mixed>  $surveyMoveModel
     * @return array<string, mixed>
     */
    public function update(string $surveyId, array $surveyMoveModel): array;
}
