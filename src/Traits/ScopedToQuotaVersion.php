<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\QuotaVersionScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements the quota-version half of {@see QuotaVersionScopedInterface};
 * use it alongside {@see ScopedToSurvey}.
 */
trait ScopedToQuotaVersion
{
    protected ?string $quotaVersion = null;

    public function setQuotaVersion(string $eTag): static
    {
        $this->quotaVersion = $eTag;

        return $this;
    }

    public function getQuotaVersion(): string
    {
        return $this->quotaVersion ?? throw MissingScopeException::for(static::class, 'quotaVersion');
    }
}
