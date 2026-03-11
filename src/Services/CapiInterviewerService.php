<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerAssignmentData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerResponseData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\EditCapiInterviewerRequestData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\NewCapiInterviewerRequestData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\ResetCapiInterviewerPasswordRequestData;
use Nikoleesg\NfieldAdmin\Resources\CapiInterviewerResource;

/**
 * CapiInterviewerService - Manage CAPI (Computer-Assisted Personal Interview) Interviewers
 *
 * This service provides methods to:
 * - List and search interviewers
 * - Create new interviewers
 * - Update interviewer details
 * - Delete interviewers
 * - Manage interviewer office assignments
 * - Manage interviewer task assignments
 */
class CapiInterviewerService
{
    public function __construct(
        protected CapiInterviewersCollectionEndpointInterface $capiInterviewersCollectionEndpoint,
        protected CapiInterviewersEndpointInterface $capiInterviewersEndpoint,
    ) {
    }

    /**
     * List all CAPI interviewers
     */
    public function listCapiInterviewers(): Collection
    {
        return CapiInterviewerData::collect(
            $this->capiInterviewersCollectionEndpoint->list(),
            Collection::class
        );
    }

    /**
     * Find CAPI interviewers with filter criteria
     */
    public function findCapiInterviewers(array $filter = []): Collection
    {
        return CapiInterviewerData::collect(
            $this->capiInterviewersCollectionEndpoint->find($filter),
            Collection::class
        );
    }

    /**
     * Create a new CAPI interviewer
     */
    public function createCapiInterviewer(NewCapiInterviewerRequestData $data): CapiInterviewerResponseData
    {
        return CapiInterviewerResponseData::from(
            $this->capiInterviewersCollectionEndpoint->create($data)
        );
    }

    /**
     * Get a CAPI interviewer by client interviewer ID
     */
    public function getByClientId(string $clientInterviewerId): CapiInterviewerData
    {
        return CapiInterviewerData::from(
            $this->capiInterviewersCollectionEndpoint->getByClientId($clientInterviewerId)
        );
    }

    /**
     * Get a specific CAPI interviewer by interviewer ID
     */
    public function getCapiInterviewer(string $interviewerId): CapiInterviewerData
    {
        return CapiInterviewerData::from($this->capiInterviewersEndpoint->get($interviewerId));
    }

    /**
     * Update (partial) a CAPI interviewer
     */
    public function updateCapiInterviewer(string $interviewerId, EditCapiInterviewerRequestData $data): CapiInterviewerResponseData
    {
        return CapiInterviewerResponseData::from(
            $this->capiInterviewersEndpoint->update($interviewerId, $data)
        );
    }

    /**
     * Reset a CAPI interviewer's password
     */
    public function resetPassword(string $interviewerId, ResetCapiInterviewerPasswordRequestData $data): CapiInterviewerResponseData
    {
        return CapiInterviewerResponseData::from(
            $this->capiInterviewersEndpoint->resetPassword($interviewerId, $data)
        );
    }

    /**
     * Delete a CAPI interviewer
     */
    public function deleteCapiInterviewer(string $interviewerId): bool
    {
        return $this->capiInterviewersEndpoint->delete($interviewerId);
    }

    /**
     * Get assignments for a CAPI interviewer
     */
    public function getAssignments(string $interviewerId): Collection
    {
        return CapiInterviewerAssignmentData::collect(
            $this->capiInterviewersEndpoint->getAssignments($interviewerId),
            Collection::class
        );
    }

    /**
     * Get offices for a CAPI interviewer
     */
    public function getOffices(string $interviewerId): Collection
    {
        return collect($this->capiInterviewersEndpoint->getOffices($interviewerId));
    }

    /**
     * Add a fieldwork office assignment
     */
    public function updateOffice(string $interviewerId, string $officeId): bool
    {
        return $this->capiInterviewersEndpoint->updateOffice($interviewerId, $officeId);
    }

    /**
     * Delete an office assignment
     */
    public function deleteOffice(string $interviewerId, string $officeId): bool
    {
        return $this->capiInterviewersEndpoint->deleteOffice($interviewerId, $officeId);
    }

    /**
     * Get fluent resource for a specific interviewer
     */
    public function for(string $interviewerId): CapiInterviewerResource
    {
        return (new CapiInterviewerResource($this->capiInterviewersEndpoint))
            ->setInterviewerId($interviewerId);
    }

    /**
     * Alias for()
     */
    public function forInterviewer(string $interviewerId): CapiInterviewerResource
    {
        return $this->for($interviewerId);
    }
}
