<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SamplingPointAddressCollectionEndpointInterface
{
    /**
     * Retrieves a list of addresses.
     * @param string $surveyId
     * @param string $samplingPointId
     * @return array
     */
    public function list(string $surveyId, string $samplingPointId): array;

    /**
     * Retrieves a list of addresses, filter and sorted using standard OData syntax
     * @param string $surveyId
     * @param string $samplingPointId
     * @param array $data
     * @return array
     */
    public function find(string $surveyId, string $samplingPointId, array $data): array;

    /**
     * Add a new address to the specified sampling point.
     * @param string $surveyId
     * @param string $samplingPointId
     * @param array $addressModel
     * @return array
     */
    public function create(string $surveyId, string $samplingPointId, array $addressModel): array;

}
