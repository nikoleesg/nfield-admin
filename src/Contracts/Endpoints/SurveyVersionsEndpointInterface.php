<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyVersionsEndpointInterface
{
    /**
     * The published versions (eTags) of a survey.
     *
     * @return list<array<string, mixed>>
     */
    public function list(string $surveyId): array;
}
