<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\NewCapiInterviewerRequestData;

interface CapiInterviewersCollectionEndpointInterface
{
    /**
     * Get all CAPI interviewers
     *
     * @return array
     */
    public function list(): array;

    /**
     * Find CAPI interviewers with filters
     *
     * @param array $data
     * @return array
     */
    public function find(array $data = []): array;

    /**
     * Create a new CAPI interviewer
     *
     * @param NewCapiInterviewerRequestData $data
     * @return array
     */
    public function create(NewCapiInterviewerRequestData $data): array;

    /**
     * Get CAPI interviewer by client interviewer ID
     *
     * @param string $clientInterviewerId
     * @return array
     */
    public function getByClientId(string $clientInterviewerId): array;
}
