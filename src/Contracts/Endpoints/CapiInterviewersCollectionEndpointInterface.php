<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface CapiInterviewersCollectionEndpointInterface
{
    /**
     * Get all CAPI interviewers
     *
     * @return list<array<string, mixed>>
     */
    public function list(): array;

    /**
     * Find CAPI interviewers with filters
     *
     * @param  array<string, mixed>  $data
     * @return list<array<string, mixed>>
     */
    public function find(array $data = []): array;

    /**
     * Create a new CAPI interviewer
     *
     * @param  array<string, mixed>  $newCapiInterviewerRequestData
     * @return array<string, mixed>
     */
    public function create(array $newCapiInterviewerRequestData): array;

    /**
     * Get CAPI interviewer by client interviewer ID
     *
     * @return array<string, mixed>
     */
    public function getByClientId(string $clientInterviewerId): array;
}
