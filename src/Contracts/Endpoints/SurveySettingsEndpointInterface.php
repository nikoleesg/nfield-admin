<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveySettingsEndpointInterface
{
    /**
     * Retrieves all key-value settings for a survey.
     * GET /v1/surveys/{surveyId}/settings
     */
    public function listSettings(string $surveyId): array;

    /**
     * Adds or updates a single key-value setting for a survey.
     * POST /v1/surveys/{surveyId}/settings
     */
    public function addOrUpdateSetting(string $surveyId, array $setting): array;

    /**
     * Retrieves the general settings for a survey.
     * GET /v1/surveys/{surveyId}/generalSettings
     */
    public function getGeneralSettings(string $surveyId): array;

    /**
     * Partially updates the general settings for a survey.
     * PATCH /v1/surveys/{surveyId}/generalSettings
     */
    public function updateGeneralSettings(string $surveyId, array $data): void;
}
