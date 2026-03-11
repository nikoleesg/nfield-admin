<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\EditCapiInterviewerRequestData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\ResetCapiInterviewerPasswordRequestData;

interface CapiInterviewersEndpointInterface
{
    /**
     * Get a specific CAPI interviewer
     *
     * @param string $interviewerId
     * @return array
     */
    public function get(string $interviewerId): array;

    /**
     * Delete a CAPI interviewer
     *
     * @param string $interviewerId
     * @return bool
     */
    public function delete(string $interviewerId): bool;

    /**
     * Update (partial) a CAPI interviewer
     *
     * @param string $interviewerId
     * @param EditCapiInterviewerRequestData $data
     * @return array
     */
    public function update(string $interviewerId, EditCapiInterviewerRequestData $data): array;

    /**
     * Reset a CAPI interviewer's password (PUT)
     *
     * @param string $interviewerId
     * @param ResetCapiInterviewerPasswordRequestData $data
     * @return array
     */
    public function resetPassword(string $interviewerId, ResetCapiInterviewerPasswordRequestData $data): array;

    /**
     * Get assignments for a CAPI interviewer
     *
     * @param string $interviewerId
     * @return array
     */
    public function getAssignments(string $interviewerId): array;

    /**
     * Get offices for a CAPI interviewer
     *
     * @param string $interviewerId
     * @return array
     */
    public function getOffices(string $interviewerId): array;

    /**
     * Update (patch) an office assignment
     *
     * @param string $interviewerId
     * @param string $officeId
     * @return bool
     */
    public function updateOffice(string $interviewerId, string $officeId): bool;

    /**
     * Delete an office assignment
     *
     * @param string $interviewerId
     * @param string $officeId
     * @return bool
     */
    public function deleteOffice(string $interviewerId, string $officeId): bool;
}
