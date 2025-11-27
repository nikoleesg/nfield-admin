<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyAssignmentEndpointInterface
{
    /**
     * Assign many interviewers to many sampling points
     * @param string $surveyId
     * @param array $data
     * @return array
     */
    public function massAssign(string $surveyId, array $data): array;

    /**
     * Unassign many interviewers from many sampling points
     * @param string $surveyId
     * @param array $data
     * @return array
     */
    public function massUnassign(string $surveyId, array $data): array;
}
