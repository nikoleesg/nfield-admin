<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyGeneralSettingsEndpointInterface
{
    /**
     * Retrieves the general settings for a survey.
     * GET /v2/surveys/{surveyId}/generalSettings
     *
     * @return array<string, mixed>
     */
    public function get(string $surveyId): array;

    /**
     * Partially updates the general settings for a survey.
     * PATCH /v2/surveys/{surveyId}/generalSettings
     *
     * @param  array<string, mixed>  $data
     */
    public function update(string $surveyId, array $data): void;
}
