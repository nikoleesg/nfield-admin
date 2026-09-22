<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveySamplingPointsAssignmentsEndpointInterface
{
    /**
     * Assign many interviewers to many sampling points
     */
    public function massAssign(string $surveyId, array $data): array;

    /**
     * Unassign many interviewers from many sampling points
     */
    public function massUnassign(string $surveyId, array $data): array;
}
