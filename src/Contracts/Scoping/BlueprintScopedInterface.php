<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service or resource that operates on a single blueprint survey.
 */
interface BlueprintScopedInterface
{
    public function setBlueprintId(string $blueprintId): static;

    public function getBlueprintId(): string;
}
