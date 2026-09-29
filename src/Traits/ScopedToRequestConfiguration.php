<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\RequestConfigurationScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements {@see RequestConfigurationScopedInterface}.
 */
trait ScopedToRequestConfiguration
{
    protected ?int $requestConfigurationId = null;

    public function setRequestConfigurationId(int $requestConfigurationId): static
    {
        $this->requestConfigurationId = $requestConfigurationId;

        return $this;
    }

    public function getRequestConfigurationId(): int
    {
        return $this->requestConfigurationId ?? throw MissingScopeException::for(static::class, 'requestConfigurationId');
    }
}
