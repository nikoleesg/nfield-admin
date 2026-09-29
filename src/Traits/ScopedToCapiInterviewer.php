<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\CapiInterviewerScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements {@see CapiInterviewerScopedInterface}.
 */
trait ScopedToCapiInterviewer
{
    protected ?string $interviewerId = null;

    public function setInterviewerId(string $interviewerId): static
    {
        $this->interviewerId = $interviewerId;

        return $this;
    }

    public function getInterviewerId(): string
    {
        return $this->interviewerId ?? throw MissingScopeException::for(static::class, 'interviewerId');
    }
}
