<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveySamplingMethodEndpointInterface
{
    /**
     * Retrieve current SamplingMethod
     *
     * @return array<string, mixed>
     */
    public function get(string $surveyId): array;

    /**
     * Saving the SamplingMethod
     *
     * @param  array<string, mixed>  $data
     */
    public function update(string $surveyId, array $data): void;
}
