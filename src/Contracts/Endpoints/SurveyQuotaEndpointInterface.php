<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyQuotaEndpointInterface
{
    /**
     * Retrieve the quota definition.
     * @param string $surveyId
     * @return array
     */
    public function getQuotaFrame(string $surveyId): array;

    /**
     * Create or updates the survey quota frame
     * @param string $surveyId
     * @param array $data
     * @return array
     */
    public function setQuotaFrame(string $surveyId, array $data): array;

    /**
     * Update the survey quota targets for the specified quota frame version.
     * @param string $surveyId
     * @param string $eTag
     * @param array $data
     * @return array
     */
    public function setQuotaLevelsTargets(string $surveyId, string $eTag, array $data): array;

    /**
     * Retrieves a FULL QuotaFrame structure based on survey (The successful counts are not retrieved)
     * @param string $surveyId
     * @return array
     */
    public function getQuotaTargets(string $surveyId): array;

    /**
     * Retrieves a FULL QuotaFrame structure based on survey (The successful counts are also retrieved)
     * @param string $surveyId
     * @param int $eTag
     * @return array
     */
    public function getQuotaTargetsByETag(string $surveyId, int $eTag): array;

    /**
     * Retrieves a list of quota frame version for the specified survey
     * @param string $surveyId
     * @return array
     */
    public function getQuotaVersions(string $surveyId): array;

    /**
     * Retrieves quota frame for specified version
     * @param string $surveyId
     * @param int $eTag
     * @return array
     */
    public function getQuotaVersionsByETag(string $surveyId, int $eTag): array;


}
