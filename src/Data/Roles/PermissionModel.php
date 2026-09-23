<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Roles;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

class PermissionModel extends Data
{
    /**
     * @param  list<PermissionModel>|null  $dependencies  Permissions that this permission depends on.
     * @param  list<string>|null  $partOfChannels  Channels this permission belongs to.
     * @param  list<string>|null  $partOfRoles  Roles this permission belongs to.
     */
    public function __construct(
        public ?string $value,
        public ?string $displayName,
        #[DataCollectionOf(PermissionModel::class)]
        public ?array $dependencies,
        public ?array $partOfChannels,
        public ?array $partOfRoles,
    ) {}
}
