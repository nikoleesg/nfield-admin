<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\InterviewScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements the interview half of {@see InterviewScopedInterface};
 * use it alongside {@see ScopedToSurvey}.
 */
trait ScopedToInterview
{
    protected ?int $interviewId = null;

    public function setInterviewId(int $interviewId): static
    {
        $this->interviewId = $interviewId;

        return $this;
    }

    public function getInterviewId(): int
    {
        return $this->interviewId ?? throw MissingScopeException::for(static::class, 'interviewId');
    }
}
