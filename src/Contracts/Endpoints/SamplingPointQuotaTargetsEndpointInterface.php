<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SamplingPointQuotaTargetsEndpointInterface
{
    /**
     * Retrieves a list of quota level targets based on survey and sampling point
     */
    public function list(string $surveyId, string $samplingPointId): array;

    /**
     * Retrieves detail of quota level targets based on a survey and sampling point
     */
    public function get(string $surveyId, string $samplingPointId, string $quotaLevelId): array;

    /**
     * Update a sampling point's quota level with specified fields
     */
    public function update(string $surveyId, string $samplingPointId, string $quotaLevelId, array $data): array;
}
