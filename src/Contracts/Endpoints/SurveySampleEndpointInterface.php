<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveySampleEndpointInterface
{
    /**
     * Retrieves a single sample record for the specified survey
     */
    public function get(string $surveyId, int $interviewId): string;
}
