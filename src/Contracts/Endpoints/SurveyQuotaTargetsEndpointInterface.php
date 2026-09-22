<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyQuotaTargetsEndpointInterface
{
    /**
     * Retrieves a FULL QuotaFrame structure based on survey (The successful counts are not retrieved)
     */
    public function getQuotaTargets(string $surveyId): array;

    /**
     * Retrieves a FULL QuotaFrame structure based on survey (The successful counts are also retrieved)
     */
    public function getQuotaTargetsByETag(string $surveyId, int $eTag): array;
}
