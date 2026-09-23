<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface RolesEndpointInterface
{
    /**
     * Get all roles and their permissions.
     */
    public function list(): array;
}
