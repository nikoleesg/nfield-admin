<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SamplingPointAddressCollectionEndpointInterface
{
    /**
     * Retrieves a list of addresses.
     *
     * @return list<array<string, mixed>>
     */
    public function list(string $surveyId, string $samplingPointId): array;

    /**
     * Retrieves a list of addresses, filter and sorted using standard OData syntax
     *
     * @param  array<string, mixed>  $data
     * @return list<array<string, mixed>>
     */
    public function find(string $surveyId, string $samplingPointId, array $data = []): array;

    /**
     * Add a new address to the specified sampling point.
     *
     * @param  array<string, mixed>  $addressModel
     * @return array<string, mixed>
     */
    public function create(string $surveyId, string $samplingPointId, array $addressModel): array;
}
