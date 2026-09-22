<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SamplingPointAssignmentEndpointInterface
{
    /**
     * Get the interviewers assigned to a sampling point
     */
    public function list(string $surveyId, string $samplingPointId): array;

    /**
     * Assign an interviewer to a sampling point
     */
    public function assign(string $surveyId, string $samplingPointId, string $interviewerId): array;

    /**
     * Unassign an interviewer from a sampling point
     */
    public function unassign(string $surveyId, string $samplingPointId, string $interviewerId): void;
}
