<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\QuotaLevelScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements the quota-level half of {@see QuotaLevelScopedInterface};
 * use it alongside {@see ScopedToSamplingPoint} and {@see ScopedToSurvey}.
 */
trait ScopedToQuotaLevel
{
    protected ?string $quotaLevelId = null;

    public function setQuotaLevelId(string $quotaLevelId): static
    {
        $this->quotaLevelId = $quotaLevelId;

        return $this;
    }

    public function getQuotaLevelId(): string
    {
        return $this->quotaLevelId ?? throw MissingScopeException::for(static::class, 'quotaLevelId');
    }
}
