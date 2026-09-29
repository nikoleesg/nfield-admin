<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service that operates on one *REQUEST configuration. The id is an int32.
 */
interface RequestConfigurationScopedInterface
{
    public function setRequestConfigurationId(int $requestConfigurationId): static;

    public function getRequestConfigurationId(): int;
}
