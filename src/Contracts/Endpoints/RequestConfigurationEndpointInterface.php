<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface RequestConfigurationEndpointInterface
{
    /**
     * One request configuration.
     *
     * @return array<string, mixed>
     */
    public function get(int $requestId): array;

    /**
     * Delete a request configuration.
     */
    public function delete(int $requestId): void;
}
