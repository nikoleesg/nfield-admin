<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface CapiInterviewersAssignmentsEndpointInterface
{
    /**
     * Get assignments for a CAPI interviewer
     */
    public function list(string $interviewerId): array;
}
