<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersAssignmentsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersOfficesEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerAssignmentModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerResponseModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\EditCapiInterviewerRequestModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\NewCapiInterviewerRequestModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\ResetCapiInterviewerPasswordRequestModel;
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
        protected CapiInterviewersAssignmentsEndpointInterface $capiInterviewersAssignmentsEndpoint,
        protected CapiInterviewersOfficesEndpointInterface $capiInterviewersOfficesEndpoint,
    ) {}

    /**
     * List all CAPI interviewers
     */
    /** @return Collection<int, CapiInterviewerModel> */
    public function list(): Collection
    {
        return CapiInterviewerModel::collect(
            $this->capiInterviewersCollectionEndpoint->list(),
            Collection::class
        );
    }

    /**
     * Find CAPI interviewers with filter criteria
     */
    /** @return Collection<int, CapiInterviewerModel> */
    public function find(array $filter = []): Collection
    {
        return CapiInterviewerModel::collect(
            $this->capiInterviewersCollectionEndpoint->find($filter),
            Collection::class
        );
    }

    /**
     * Create a new CAPI interviewer
     */
    public function create(array|NewCapiInterviewerRequestModel $data): CapiInterviewerResponseModel
    {
        $payload = NewCapiInterviewerRequestModel::from($data)->toArray();

        return CapiInterviewerResponseModel::from(
            $this->capiInterviewersCollectionEndpoint->create($payload)
        );
    }

    /**
     * Get a CAPI interviewer by client interviewer ID
     */
    public function getByClientId(string $clientInterviewerId): CapiInterviewerModel
    {
        return CapiInterviewerModel::from(
            $this->capiInterviewersCollectionEndpoint->getByClientId($clientInterviewerId)
        );
    }

    /**
     * Get a specific CAPI interviewer by interviewer ID
     */
    public function get(string $interviewerId): CapiInterviewerModel
    {
        return CapiInterviewerModel::from($this->capiInterviewersEndpoint->get($interviewerId));
    }

    /**
     * Update (partial) a CAPI interviewer
     */
    public function update(string $interviewerId, array|EditCapiInterviewerRequestModel $data): CapiInterviewerResponseModel
    {
        $payload = EditCapiInterviewerRequestModel::from($data)->toArray();

        return CapiInterviewerResponseModel::from(
            $this->capiInterviewersEndpoint->update($interviewerId, $payload)
        );
    }

    /**
     * Reset a CAPI interviewer's password
     */
    public function resetPassword(string $interviewerId, array|ResetCapiInterviewerPasswordRequestModel $data): CapiInterviewerResponseModel
    {
        $payload = ResetCapiInterviewerPasswordRequestModel::from($data)->toArray();

        return CapiInterviewerResponseModel::from(
            $this->capiInterviewersEndpoint->resetPassword($interviewerId, $payload)
        );
    }

    /**
     * Delete a CAPI interviewer
     */
    public function delete(string $interviewerId): void
    {
        $this->capiInterviewersEndpoint->delete($interviewerId);
    }

    /**
     * Get assignments for a CAPI interviewer
     */
    /** @return Collection<int, CapiInterviewerAssignmentModel> */
    public function getAssignments(string $interviewerId): Collection
    {
        return CapiInterviewerAssignmentModel::collect(
            $this->capiInterviewersAssignmentsEndpoint->list($interviewerId),
            Collection::class
        );
    }

    /**
     * Get offices for a CAPI interviewer
     */
    /** @return Collection<int, string> */
    public function getOffices(string $interviewerId): Collection
    {
        return collect($this->capiInterviewersOfficesEndpoint->list($interviewerId));
    }

    /**
     * Add a fieldwork office assignment
     */
    public function updateOffice(string $interviewerId, string $officeId): void
    {
        $this->capiInterviewersOfficesEndpoint->update($interviewerId, $officeId);
    }

    /**
     * Delete an office assignment
     */
    public function deleteOffice(string $interviewerId, string $officeId): void
    {
        $this->capiInterviewersOfficesEndpoint->delete($interviewerId, $officeId);
    }

    /**
     * Get fluent resource for a specific interviewer
     */
    public function forInterviewer(string $interviewerId): CapiInterviewerResource
    {
        return (new CapiInterviewerResource(
            $this->capiInterviewersEndpoint,
            $this->capiInterviewersAssignmentsEndpoint,
            $this->capiInterviewersOfficesEndpoint,
        ))
            ->setInterviewerId($interviewerId);
    }
}
