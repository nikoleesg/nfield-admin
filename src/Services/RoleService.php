<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\RolesEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\UserRoleEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Roles\PermissionModel;
use Nikoleesg\NfieldAdmin\Data\Roles\UserRoleModel;
use Nikoleesg\NfieldAdmin\Services\Http\ResponseKeyNormalizer;

class RoleService
{
    public function __construct(
        protected UserRoleEndpointInterface $userRoleEndpoint,
        protected RolesEndpointInterface $rolesEndpoint,
    ) {}

    /**
     * Get the current user's role and permissions.
     */
    public function getUserRole(): UserRoleModel
    {
        return UserRoleModel::from($this->userRoleEndpoint->get());
    }

    /**
     * Get all roles and their associated permissions.
     *
     * Returns a map of role name → collection of permissions. The dictionary
     * keys are role names defined in the NField domain and are preserved
     * exactly as the API returns them.
     *
     * @return Collection<string, Collection<int, PermissionModel>>
     */
    public function listRoles(): Collection
    {
        /** @var array<string, array<int, array<string, mixed>>> $response */
        $response = $this->rolesEndpoint->list();

        /** @var Collection<string, Collection<int, PermissionModel>> $collection */
        $collection = collect($response)->map(
            fn (array $permissions) => collect($permissions)->map(
                fn (array $item) => PermissionModel::from(
                    ResponseKeyNormalizer::normalize($item)
                )
            )->values()
        );

        return $collection;
    }
}
