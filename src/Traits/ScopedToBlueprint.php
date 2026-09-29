<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\BlueprintScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements {@see BlueprintScopedInterface}.
 */
trait ScopedToBlueprint
{
    protected ?string $blueprintId = null;

    public function setBlueprintId(string $blueprintId): static
    {
        $this->blueprintId = $blueprintId;

        return $this;
    }

    public function getBlueprintId(): string
    {
        return $this->blueprintId ?? throw MissingScopeException::for(static::class, 'blueprintId');
    }
}
