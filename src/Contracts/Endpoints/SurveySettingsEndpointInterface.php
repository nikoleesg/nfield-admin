<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveySettingsEndpointInterface
{
    /**
     * Retrieves all key-value settings for a survey.
     * GET /v2/surveys/{surveyId}/settings
     *
     * @return list<array<string, mixed>>
     */
    public function listSettings(string $surveyId): array;

    /**
     * Adds or updates a single key-value setting for a survey.
     * POST /v2/surveys/{surveyId}/settings
     *
     * @param  array<string, mixed>  $setting
     * @return array<string, mixed>
     */
    public function addOrUpdateSetting(string $surveyId, array $setting): array;
}
