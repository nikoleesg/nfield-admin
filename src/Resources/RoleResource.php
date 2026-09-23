<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Data\Roles\PermissionModel;
use Nikoleesg\NfieldAdmin\Data\Roles\UserRoleModel;
use Nikoleesg\NfieldAdmin\Services\RoleService;

class RoleResource
{
    protected ?RoleService $service = null;

    protected function resolveService(): RoleService
    {
        if ($this->service === null) {
            $this->service = app(RoleService::class);
        }

        return $this->service;
    }

    /**
     * Get the current user's role and permissions.
     */
    public function getUserRole(): UserRoleModel
    {
        return $this->resolveService()->getUserRole();
    }

    /**
     * Get all roles and their associated permissions.
     *
     * @return Collection<string, Collection<int, PermissionModel>>
     */
    public function list(): Collection
    {
        return $this->resolveService()->list();
    }
}
