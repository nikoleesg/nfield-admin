<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service or resource that operates on a single CAPI interviewer.
 */
interface CapiInterviewerScopedInterface
{
    public function setInterviewerId(string $interviewerId): static;

    public function getInterviewerId(): string;
}
