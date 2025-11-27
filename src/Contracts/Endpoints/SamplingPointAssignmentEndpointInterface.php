<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SamplingPointAssignmentEndpointInterface
{
    /**
     * Get the interviewers assigned to a sampling point
     * @param string $surveyId
     * @param string $samplingPointId
     * @return array
     */
    public function list(string $surveyId, string $samplingPointId): array;

    /**
     * Assign an interviewer to a sampling point
     * @param string $surveyId
     * @param string $samplingPointId
     * @param string $interviewerId
     * @return array
     */
    public function assign(string $surveyId, string $samplingPointId, string $interviewerId): array;

    /**
     * Unassign an interviewer from a sampling point
     * @param string $surveyId
     * @param string $samplingPointId
     * @param string $interviewerId
     * @return bool
     */
    public function unassign(string $surveyId, string $samplingPointId, string $interviewerId): bool;
}
