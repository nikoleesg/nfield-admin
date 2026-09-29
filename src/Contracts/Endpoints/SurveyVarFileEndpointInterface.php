<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyVarFileEndpointInterface
{
    /**
     * The survey's current var file.
     *
     * @return array<string, mixed>
     */
    public function get(string $surveyId): array;

    /**
     * The var file of one published version (eTag) of the survey.
     *
     * @return array<string, mixed>
     */
    public function getVersion(string $surveyId, string $eTag): array;
}
