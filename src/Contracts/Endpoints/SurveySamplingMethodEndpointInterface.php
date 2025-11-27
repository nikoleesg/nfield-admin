<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveySamplingMethodEndpointInterface
{
    /**
     * Retrieve current SamplingMethod
     * @param string $surveyId
     * @return array
     */
    public function get(string $surveyId): array;

    /**
     * Saving the SamplingMethod
     * @param string $surveyId
     * @param array $data
     * @return bool
     */
    public function update(string $surveyId, array $data): bool;
}
