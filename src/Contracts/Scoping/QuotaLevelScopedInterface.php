<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service that operates on one quota level of a sampling point.
 *
 * Every quota level belongs to a sampling point, so this contract extends
 * {@see SamplingPointScopedInterface}. The level id is a string.
 */
interface QuotaLevelScopedInterface extends SamplingPointScopedInterface
{
    public function setQuotaLevelId(string $quotaLevelId): static;

    public function getQuotaLevelId(): string;
}
