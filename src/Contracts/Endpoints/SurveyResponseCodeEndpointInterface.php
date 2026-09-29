<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyResponseCodeEndpointInterface
{
    /**
     * One response code of a survey.
     *
     * @return array<string, mixed>
     */
    public function get(string $surveyId, int $responseCode): array;

    /**
     * Update a survey response code with the given fields.
     *
     * @param  array<string, mixed>  $surveyResponseCodeUpdateModel
     * @return array<string, mixed>
     */
    public function update(string $surveyId, int $responseCode, array $surveyResponseCodeUpdateModel): array;

    /**
     * Delete a survey response code.
     */
    public function delete(string $surveyId, int $responseCode): void;
}
