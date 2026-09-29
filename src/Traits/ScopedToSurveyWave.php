<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyWaveScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements {@see SurveyWaveScopedInterface}.
 */
trait ScopedToSurveyWave
{
    protected ?string $waveId = null;

    public function setWaveId(string $waveId): static
    {
        $this->waveId = $waveId;

        return $this;
    }

    public function getWaveId(): string
    {
        return $this->waveId ?? throw MissingScopeException::for(static::class, 'waveId');
    }
}
