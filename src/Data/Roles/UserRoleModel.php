<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Roles;

use Spatie\LaravelData\Data;

class UserRoleModel extends Data
{
    /**
     * @param  list<string>|null  $userRoles  The user roles for the currently authenticated user, ordered from most to least powerful.
     * @param  list<string>|null  $permissions  The permissions for the currently authenticated session.
     */
    public function __construct(
        public ?array $userRoles,
        public ?array $permissions,
    ) {}
}
