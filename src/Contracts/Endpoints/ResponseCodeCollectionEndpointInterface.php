<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface ResponseCodeCollectionEndpointInterface
{
    /**
     * All domain response codes.
     *
     * @return list<array<string, mixed>>
     */
    public function list(): array;

    /**
     * Create a domain response code.
     *
     * @param  array<string, mixed>  $domainResponseCodeCreateModel
     * @return array<string, mixed>
     */
    public function create(array $domainResponseCodeCreateModel): array;
}
