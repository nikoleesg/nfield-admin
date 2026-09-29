<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyQuotaVersionsEndpointInterface
{
    /**
     * Retrieves a list of quota frame version for the specified survey
     *
     * @return list<array<string, mixed>>
     */
    public function list(string $surveyId): array;

    /**
     * Retrieves quota frame for specified version
     *
     * @return array<string, mixed>
     */
    public function get(string $surveyId, string $eTag): array;
}
