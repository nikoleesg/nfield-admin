<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SamplingPointAddressEndpointInterface
{
    /**
     * Retrieve the details of a single address.
     * @param string $surveyId
     * @param string $samplingPointId
     * @param string $addressId
     * @return array
     */
    public function get(string $surveyId, string $samplingPointId, string $addressId): array;

    /**
     * Delete a specific address.
     * @param string $surveyId
     * @param string $samplingPointId
     * @param string $addressId
     * @return bool
     */
    public function delete(string $surveyId, string $samplingPointId, string $addressId): bool;
}
