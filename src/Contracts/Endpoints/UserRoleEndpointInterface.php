<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface UserRoleEndpointInterface
{
    /**
     * Get the current user's role and permissions.
     */
    public function get(): array;
}
