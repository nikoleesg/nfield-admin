<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveySamplingMethodEndpointInterface
{
    /**
     * Retrieve current SamplingMethod
     */
    public function get(string $surveyId): array;

    /**
     * Saving the SamplingMethod
     */
    public function update(string $surveyId, array $data): void;
}
