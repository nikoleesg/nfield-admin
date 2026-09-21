<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyQuotaEndpointInterface
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

    /**
     * Retrieves a FULL QuotaFrame structure based on survey (The successful counts are not retrieved)
     */
    public function getQuotaTargets(string $surveyId): array;

    /**
     * Retrieves a FULL QuotaFrame structure based on survey (The successful counts are also retrieved)
     */
    public function getQuotaTargetsByETag(string $surveyId, int $eTag): array;

    /**
     * Retrieves a list of quota frame version for the specified survey
     */
    public function getQuotaVersions(string $surveyId): array;

    /**
     * Retrieves quota frame for specified version
     */
    public function getQuotaVersionsByETag(string $surveyId, int $eTag): array;
}
