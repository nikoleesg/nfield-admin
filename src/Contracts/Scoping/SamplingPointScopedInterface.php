<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service or resource that operates inside a single sampling point.
 *
 * Every sampling point lives under a survey, so this contract extends
 * {@see SurveyScopedInterface} rather than standing on its own.
 */
interface SamplingPointScopedInterface extends SurveyScopedInterface
{
    public function setSamplingPointId(string $samplingPointId): static;

    public function getSamplingPointId(): string;
}
