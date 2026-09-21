<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SamplingPointAddressEndpointInterface
{
    /**
     * Retrieve the details of a single address.
     */
    public function get(string $surveyId, string $samplingPointId, string $addressId): array;

    /**
     * Delete a specific address.
     */
    public function delete(string $surveyId, string $samplingPointId, string $addressId): bool;
}
