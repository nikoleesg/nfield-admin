<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyScriptEndpointInterface
{
    /**
     * The survey's current ODIN script.
     *
     * @return array<string, mixed>
     */
    public function get(string $surveyId): array;

    /**
     * The ODIN script of one published version (eTag) of the survey.
     *
     * @return array<string, mixed>
     */
    public function getVersion(string $surveyId, string $eTag): array;

    /**
     * Replace the survey's ODIN script.
     *
     * @param  array<string, mixed>  $surveySetScriptModel
     * @return array<string, mixed>
     */
    public function update(string $surveyId, array $surveySetScriptModel): array;
}
