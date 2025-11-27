<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SamplingPointQuotaTargetsEndpointInterface
{
    /**
     * Retrieves a list of quota level targets based on survey and sampling point
     * @param string $surveyId
     * @param string $samplingPointId
     * @return array
     */
    public function list(string $surveyId, string $samplingPointId): array;

    /**
     * Retrieves detail of quota level targets based on a survey and sampling point
     * @param string $surveyId
     * @param string $samplingPointId
     * @param string $quotaLevelId
     * @return array
     */
    public function get(string $surveyId, string $samplingPointId, string $quotaLevelId): array;

    /**
     * Update a sampling point's quota level with specified fields
     * @param string $surveyId
     * @param string $samplingPointId
     * @param string $quotaLevelId
     * @param array $data
     * @return array
     */
    public function update(string $surveyId, string $samplingPointId, string $quotaLevelId, array $data): array;
}
