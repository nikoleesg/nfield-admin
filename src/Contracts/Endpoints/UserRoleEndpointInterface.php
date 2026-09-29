<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface UserRoleEndpointInterface
{
    /**
     * Get the current user's role and permissions.
     *
     * @return array<string, mixed>
     */
    public function get(): array;
}
