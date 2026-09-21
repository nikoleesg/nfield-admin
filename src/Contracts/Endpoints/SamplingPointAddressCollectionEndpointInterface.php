<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SamplingPointAddressCollectionEndpointInterface
{
    /**
     * Retrieves a list of addresses.
     */
    public function list(string $surveyId, string $samplingPointId): array;

    /**
     * Retrieves a list of addresses, filter and sorted using standard OData syntax
     */
    public function find(string $surveyId, string $samplingPointId, array $data): array;

    /**
     * Add a new address to the specified sampling point.
     */
    public function create(string $surveyId, string $samplingPointId, array $addressModel): array;
}
