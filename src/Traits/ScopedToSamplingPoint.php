<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\SamplingPointScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements the sampling-point half of
 * {@see SamplingPointScopedInterface};
 * use it alongside {@see ScopedToSurvey}.
 */
trait ScopedToSamplingPoint
{
    protected ?string $samplingPointId = null;

    public function setSamplingPointId(string $samplingPointId): static
    {
        $this->samplingPointId = $samplingPointId;

        return $this;
    }

    public function getSamplingPointId(): string
    {
        return $this->samplingPointId ?? throw MissingScopeException::for(static::class, 'samplingPointId');
    }
}
