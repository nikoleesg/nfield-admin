<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySamplingPointsAssignmentsEndpointInterface;

class SurveyAssignmentService
{
    public function __construct(
        protected SurveySamplingPointsAssignmentsEndpointInterface $surveySamplingPointsAssignmentsEndpoint,
        protected readonly string $surveyId,
    ) {}

    public function assignInterviewers(array $samplingPointIds, array $interviewerIds): array
    {
        $data = [
            'samplingPointIds' => $samplingPointIds,
            'interviewerIds' => $interviewerIds,
        ];

        return $this->surveySamplingPointsAssignmentsEndpoint->massAssign($this->surveyId, $data);
    }

    public function unassignInterviewers(array $samplingPointIds, array $interviewerIds): array
    {
        $data = [
            'samplingPointIds' => $samplingPointIds,
            'interviewerIds' => $interviewerIds,
        ];

        return $this->surveySamplingPointsAssignmentsEndpoint->massUnassign($this->surveyId, $data);
    }
}
