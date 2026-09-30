<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\InterviewQualityScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements the interview half of {@see InterviewQualityScopedInterface};
 * use it alongside {@see ScopedToSurvey}.
 */
trait ScopedToInterviewQuality
{
    protected ?string $qualityInterviewId = null;

    public function setQualityInterviewId(string $qualityInterviewId): static
    {
        $this->qualityInterviewId = $qualityInterviewId;

        return $this;
    }

    public function getQualityInterviewId(): string
    {
        return $this->qualityInterviewId ?? throw MissingScopeException::for(static::class, 'qualityInterviewId');
    }
}
