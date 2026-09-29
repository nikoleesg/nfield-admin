<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyDataRetentionSettingsEndpointInterface
{
    /**
     * The survey's data retention settings.
     *
     * @return array<string, mixed>
     */
    public function get(string $surveyId): array;

    /**
     * Replace the survey's data retention settings.
     *
     * @param  array<string, mixed>  $updateDataRetentionSettingsModel
     */
    public function update(string $surveyId, array $updateDataRetentionSettingsModel): void;
}
