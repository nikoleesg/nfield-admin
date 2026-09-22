<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyAssignmentEndpointInterface;

class SurveyAssignmentService
{
    public function __construct(
        protected SurveyAssignmentEndpointInterface $surveyAssignmentEndpoint,
        protected readonly string $surveyId,
    ) {}

    public function assignInterviewers(array $samplingPointIds, array $interviewerIds): array
    {
        $data = [
            'samplingPointIds' => $samplingPointIds,
            'interviewerIds' => $interviewerIds,
        ];

        return $this->surveyAssignmentEndpoint->massAssign($this->surveyId, $data);
    }

    public function unassignInterviewers(array $samplingPointIds, array $interviewerIds): array
    {
        $data = [
            'samplingPointIds' => $samplingPointIds,
            'interviewerIds' => $interviewerIds,
        ];

        return $this->surveyAssignmentEndpoint->massUnassign($this->surveyId, $data);
    }
}
