<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyQuotaFrameEndpointInterface
{
    /**
     * Retrieve the quota definition.
     *
     * @return array<string, mixed>
     */
    public function getQuotaFrame(string $surveyId): array;

    /**
     * Create or updates the survey quota frame
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function setQuotaFrame(string $surveyId, array $data): array;

    /**
     * Update the survey quota targets for the specified quota frame version.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function setQuotaLevelsTargets(string $surveyId, string $eTag, array $data): array;
}
