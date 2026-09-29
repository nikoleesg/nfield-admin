<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface RequestConfigurationCollectionEndpointInterface
{
    /**
     * The request configurations, optionally filtered by `name`. Always a
     * list, whatever shape the API answered with.
     *
     * @param  array<string, mixed>  $query
     * @return list<array<string, mixed>>
     */
    public function list(array $query = []): array;

    /**
     * Create a request configuration. The API answers 201 with no body.
     *
     * @param  array<string, mixed>  $requestConfigurationModel
     */
    public function create(array $requestConfigurationModel): void;

    /**
     * Update the request configuration whose id is in the body.
     *
     * @param  array<string, mixed>  $requestConfigurationModel
     * @return array<string, mixed>
     */
    public function update(array $requestConfigurationModel): array;
}
