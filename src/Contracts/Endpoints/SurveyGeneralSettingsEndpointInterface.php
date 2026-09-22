<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyGeneralSettingsEndpointInterface
{
    /**
     * Retrieves the general settings for a survey.
     * GET /v2/surveys/{surveyId}/generalSettings
     */
    public function getGeneralSettings(string $surveyId): array;

    /**
     * Partially updates the general settings for a survey.
     * PATCH /v2/surveys/{surveyId}/generalSettings
     */
    public function updateGeneralSettings(string $surveyId, array $data): void;
}
