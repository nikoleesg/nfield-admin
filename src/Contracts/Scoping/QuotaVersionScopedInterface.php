<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service or resource that operates on one version of a survey's quota frame.
 *
 * Every quota frame belongs to a survey, so this contract extends
 * {@see SurveyScopedInterface} rather than standing on its own. The version is
 * the frame's eTag, a string as the quota versions list returns it.
 */
interface QuotaVersionScopedInterface extends SurveyScopedInterface
{
    public function setQuotaVersion(string $eTag): static;

    public function getQuotaVersion(): string;
}
