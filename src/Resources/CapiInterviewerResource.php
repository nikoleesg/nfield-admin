<?php

namespace Nikoleesg\NfieldAdmin\Resources;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerAssignmentData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerResponseData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\EditCapiInterviewerRequestData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\ResetCapiInterviewerPasswordRequestData;

/**
 * CapiInterviewerResource - Fluent interface for individual CAPI interviewer operations
 *
 * Provides chainable methods for managing a specific CAPI interviewer's assignments
 * and office assignments.
 */
class CapiInterviewerResource
{
    protected ?string $interviewerId = null;

    public function __construct(
        protected CapiInterviewersEndpointInterface $capiInterviewersEndpoint,
    ) {}

    /**
     * Set the interviewer ID
     *
     * @param string $interviewerId
     * @return static
     */
    public function setInterviewerId(string $interviewerId): static
    {
        $this->interviewerId = $interviewerId;
        return $this;
    }

    /**
     * Get the interviewer details
     */
    public function get(): CapiInterviewerData
    {
        return CapiInterviewerData::from(
            $this->capiInterviewersEndpoint->get($this->interviewerId)
        );
    }

    /**
     * Update (partial) the interviewer
     */
    public function update(EditCapiInterviewerRequestData $data): CapiInterviewerResponseData
    {
        return CapiInterviewerResponseData::from(
            $this->capiInterviewersEndpoint->update($this->interviewerId, $data)
        );
    }

    /**
     * Reset the interviewer's password
     */
    public function resetPassword(ResetCapiInterviewerPasswordRequestData $data): CapiInterviewerResponseData
    {
        return CapiInterviewerResponseData::from(
            $this->capiInterviewersEndpoint->resetPassword($this->interviewerId, $data)
        );
    }

    /**
     * Delete the interviewer
     *
     * @return bool
     */
    public function delete(): bool
    {
        return $this->capiInterviewersEndpoint->delete($this->interviewerId);
    }

    /**
     * Get interviewer assignments (surveys/tasks)
     */
    public function getAssignments(): Collection
    {
        return CapiInterviewerAssignmentData::collect(
            $this->capiInterviewersEndpoint->getAssignments($this->interviewerId),
            Collection::class
        );
    }

    /**
     * Get interviewer's office assignments
     */
    public function getOffices(): Collection
    {
        return collect($this->capiInterviewersEndpoint->getOffices($this->interviewerId));
    }

    /**
     * Add a fieldwork office assignment
     */
    public function updateOffice(string $officeId): bool
    {
        return $this->capiInterviewersEndpoint->updateOffice($this->interviewerId, $officeId);
    }

    /**
     * Delete an office assignment
     *
     * @param string $officeId
     * @return bool
     */
    public function deleteOffice(string $officeId): bool
    {
        return $this->capiInterviewersEndpoint->deleteOffice($this->interviewerId, $officeId);
    }
}
