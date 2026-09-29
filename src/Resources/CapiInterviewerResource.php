<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersAssignmentsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersOfficesEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\CapiInterviewerScopedInterface;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerAssignmentModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerResponseModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\EditCapiInterviewerRequestModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\ResetCapiInterviewerPasswordRequestModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToCapiInterviewer;

/**
 * CapiInterviewerResource - Fluent interface for individual CAPI interviewer operations
 *
 * Provides chainable methods for managing a specific CAPI interviewer's assignments
 * and office assignments.
 */
class CapiInterviewerResource implements CapiInterviewerScopedInterface
{
    use ScopedToCapiInterviewer;

    public function __construct(
        protected CapiInterviewersEndpointInterface $capiInterviewersEndpoint,
        protected CapiInterviewersAssignmentsEndpointInterface $capiInterviewersAssignmentsEndpoint,
        protected CapiInterviewersOfficesEndpointInterface $capiInterviewersOfficesEndpoint,
    ) {}

    /**
     * Get the interviewer details
     */
    public function get(): CapiInterviewerModel
    {
        return CapiInterviewerModel::from(
            $this->capiInterviewersEndpoint->get($this->getInterviewerId())
        );
    }

    /**
     * Update (partial) the interviewer
     *
     * @param  array<string, mixed>|EditCapiInterviewerRequestModel  $data
     */
    public function update(array|EditCapiInterviewerRequestModel $data): CapiInterviewerResponseModel
    {
        $payload = EditCapiInterviewerRequestModel::from($data)->toArray();

        return CapiInterviewerResponseModel::from(
            $this->capiInterviewersEndpoint->update($this->getInterviewerId(), $payload)
        );
    }

    /**
     * Reset the interviewer's password
     *
     * @param  array<string, mixed>|ResetCapiInterviewerPasswordRequestModel  $data
     */
    public function resetPassword(array|ResetCapiInterviewerPasswordRequestModel $data): CapiInterviewerResponseModel
    {
        $payload = ResetCapiInterviewerPasswordRequestModel::from($data)->toArray();

        return CapiInterviewerResponseModel::from(
            $this->capiInterviewersEndpoint->resetPassword($this->getInterviewerId(), $payload)
        );
    }

    /**
     * Delete the interviewer
     */
    public function delete(): void
    {
        $this->capiInterviewersEndpoint->delete($this->getInterviewerId());
    }

    /**
     * Get interviewer assignments (surveys/tasks)
     *
     * @return Collection<int, CapiInterviewerAssignmentModel>
     */
    public function getAssignments(): Collection
    {
        return CapiInterviewerAssignmentModel::collect(
            $this->capiInterviewersAssignmentsEndpoint->list($this->getInterviewerId()),
            Collection::class
        );
    }

    /**
     * Get interviewer's office assignments
     *
     * @return Collection<int, string>
     */
    public function getOffices(): Collection
    {
        return collect($this->capiInterviewersOfficesEndpoint->list($this->getInterviewerId()));
    }

    /**
     * Add a fieldwork office assignment
     */
    public function updateOffice(string $officeId): void
    {
        $this->capiInterviewersOfficesEndpoint->update($this->getInterviewerId(), $officeId);
    }

    /**
     * Delete an office assignment
     */
    public function deleteOffice(string $officeId): void
    {
        $this->capiInterviewersOfficesEndpoint->delete($this->getInterviewerId(), $officeId);
    }
}
