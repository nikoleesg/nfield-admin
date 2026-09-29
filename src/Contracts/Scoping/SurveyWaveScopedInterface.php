<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service that operates on one wave of a parent survey. A wave is itself a
 * survey; waveId is its survey id.
 */
interface SurveyWaveScopedInterface
{
    public function setWaveId(string $waveId): static;

    public function getWaveId(): string;
}
