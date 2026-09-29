<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface ResponseCodeEndpointInterface
{
    /**
     * Update a domain response code with the given fields.
     *
     * @param  array<string, mixed>  $domainResponseCodeUpdateModel
     * @return array<string, mixed>
     */
    public function update(int $responseCodeId, array $domainResponseCodeUpdateModel): array;

    /**
     * Delete a domain response code.
     */
    public function delete(int $responseCodeId): void;
}
