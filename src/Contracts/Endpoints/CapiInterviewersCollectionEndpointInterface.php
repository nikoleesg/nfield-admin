<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface CapiInterviewersCollectionEndpointInterface
{
    /**
     * Get all CAPI interviewers
     */
    public function list(): array;

    /**
     * Find CAPI interviewers with filters
     */
    public function find(array $data = []): array;

    /**
     * Create a new CAPI interviewer
     */
    public function create(array $newCapiInterviewerRequestData): array;

    /**
     * Get CAPI interviewer by client interviewer ID
     */
    public function getByClientId(string $clientInterviewerId): array;
}
