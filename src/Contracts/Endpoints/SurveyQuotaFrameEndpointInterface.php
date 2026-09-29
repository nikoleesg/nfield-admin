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
    public function get(string $surveyId): array;

    /**
     * Create or updates the survey quota frame
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function update(string $surveyId, array $data): array;

    /**
     * Update the survey quota targets for the specified quota frame version.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function updateVersion(string $surveyId, string $eTag, array $data): array;
}
