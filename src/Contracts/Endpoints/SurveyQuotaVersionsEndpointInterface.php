<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyQuotaVersionsEndpointInterface
{
    /**
     * Retrieves a list of quota frame version for the specified survey
     */
    public function getQuotaVersions(string $surveyId): array;

    /**
     * Retrieves quota frame for specified version
     */
    public function getQuotaVersionsByETag(string $surveyId, int $eTag): array;
}
