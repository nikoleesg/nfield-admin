<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface CapiInterviewersOfficesEndpointInterface
{
    /**
     * Get the fieldwork offices a CAPI interviewer belongs to
     */
    public function list(string $interviewerId): array;

    /**
     * Add a fieldwork office to an interviewer
     */
    public function update(string $interviewerId, string $officeId): void;

    /**
     * Delete an office assignment
     */
    public function delete(string $interviewerId, string $officeId): void;
}
