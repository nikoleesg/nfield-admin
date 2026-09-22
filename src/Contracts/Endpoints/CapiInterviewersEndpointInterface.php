<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\EditCapiInterviewerRequestData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\ResetCapiInterviewerPasswordRequestData;

interface CapiInterviewersEndpointInterface
{
    /**
     * Get a specific CAPI interviewer
     */
    public function get(string $interviewerId): array;

    /**
     * Delete a CAPI interviewer
     */
    public function delete(string $interviewerId): bool;

    /**
     * Update (partial) a CAPI interviewer
     */
    public function update(string $interviewerId, EditCapiInterviewerRequestData $data): array;

    /**
     * Reset a CAPI interviewer's password (PUT)
     */
    public function resetPassword(string $interviewerId, ResetCapiInterviewerPasswordRequestData $data): array;

    /**
     * Get assignments for a CAPI interviewer
     */
    public function getAssignments(string $interviewerId): array;

    /**
     * Get offices for a CAPI interviewer
     */
    public function getOffices(string $interviewerId): array;

    /**
     * Update (patch) an office assignment
     */
    public function updateOffice(string $interviewerId, string $officeId): bool;

    /**
     * Delete an office assignment
     */
    public function deleteOffice(string $interviewerId, string $officeId): bool;
}
