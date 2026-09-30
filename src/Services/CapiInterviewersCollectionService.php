<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerResponseModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\NewCapiInterviewerRequestModel;

/**
 * The CAPI interviewers, reached through `NfieldManager::capiInterviewers()`.
 *
 * Services mirror the endpoint naming: this pairs with
 * CapiInterviewersCollectionEndpoint, and {@see CapiInterviewersService} with
 * CapiInterviewersEndpoint.
 */
class CapiInterviewersCollectionService
{
    public function __construct(
        protected CapiInterviewersCollectionEndpointInterface $capiInterviewersCollectionEndpoint,
    ) {}

    /**
     * List all CAPI interviewers
     *
     * @return Collection<int, CapiInterviewerModel>
     */
    public function list(): Collection
    {
        return CapiInterviewerModel::collect(
            $this->capiInterviewersCollectionEndpoint->list(),
            Collection::class
        );
    }

    /**
     * Find CAPI interviewers with filter criteria
     *
     * @param  array<string, mixed>  $filter
     * @return Collection<int, CapiInterviewerModel>
     */
    public function find(array $filter = []): Collection
    {
        return CapiInterviewerModel::collect(
            $this->capiInterviewersCollectionEndpoint->find($filter),
            Collection::class
        );
    }

    /**
     * Create a new CAPI interviewer
     *
     * @param  array<string, mixed>|NewCapiInterviewerRequestModel  $data
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
     * One CAPI interviewer.
     */
    public function forInterviewer(string $interviewerId): CapiInterviewersService
    {
        return app(CapiInterviewersService::class)->setInterviewerId($interviewerId);
    }
}
