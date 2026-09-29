<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service or resource that operates on a single sampling-point address.
 *
 * Every address lives under a sampling point, so this contract extends
 * {@see SamplingPointScopedInterface} rather than standing on its own.
 */
interface AddressScopedInterface extends SamplingPointScopedInterface
{
    public function setAddressId(string $addressId): static;

    public function getAddressId(): string;
}
