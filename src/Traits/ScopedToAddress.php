<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\AddressScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements the address half of {@see AddressScopedInterface};
 * use it alongside {@see ScopedToSamplingPoint} and {@see ScopedToSurvey}.
 */
trait ScopedToAddress
{
    protected ?string $addressId = null;

    public function setAddressId(string $addressId): static
    {
        $this->addressId = $addressId;

        return $this;
    }

    public function getAddressId(): string
    {
        return $this->addressId ?? throw MissingScopeException::for(static::class, 'addressId');
    }
}
