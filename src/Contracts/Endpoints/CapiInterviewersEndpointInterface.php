<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface CapiInterviewersEndpointInterface
{
    /**
     * Get a specific CAPI interviewer
     *
     * @return array<string, mixed>
     */
    public function get(string $interviewerId): array;

    /**
     * Delete a CAPI interviewer
     */
    public function delete(string $interviewerId): void;

    /**
     * Update (partial) a CAPI interviewer
     *
     * @param  array<string, mixed>  $editCapiInterviewerRequestData
     * @return array<string, mixed>
     */
    public function update(string $interviewerId, array $editCapiInterviewerRequestData): array;

    /**
     * Reset a CAPI interviewer's password (PUT)
     *
     * @param  array<string, mixed>  $resetCapiInterviewerPasswordRequestData
     * @return array<string, mixed>
     */
    public function resetPassword(string $interviewerId, array $resetCapiInterviewerPasswordRequestData): array;
}
