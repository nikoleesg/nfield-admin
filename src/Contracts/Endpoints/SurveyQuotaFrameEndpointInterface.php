<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyQuotaFrameEndpointInterface
{
    /**
     * Retrieve the quota definition.
     */
    public function getQuotaFrame(string $surveyId): array;

    /**
     * Create or updates the survey quota frame
     */
    public function setQuotaFrame(string $surveyId, array $data): array;

    /**
     * Update the survey quota targets for the specified quota frame version.
     */
    public function setQuotaLevelsTargets(string $surveyId, string $eTag, array $data): array;
}
