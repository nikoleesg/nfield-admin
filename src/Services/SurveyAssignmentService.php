<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyAssignmentEndpointInterface;

class SurveyAssignmentService
{
    protected ?string $surveyId = null;

    public function __construct(
        protected SurveyAssignmentEndpointInterface $surveyAssignmentEndpoint
    ) {}

    public function setSurveyId(string $surveyId): self
    {
        $this->surveyId = $surveyId;
        return $this;
    }

    public function assignInterviewers(array $samplingPointIds, array $interviewerIds): array
    {
        $data = [
            'samplingPointIds' => $samplingPointIds,
            'interviewerIds'   => $interviewerIds
        ];

        return $this->surveyAssignmentEndpoint->massAssign($this->surveyId, $data);
    }

    public function unassignInterviewers(array $samplingPointIds, array $interviewerIds): array
    {
        $data = [
            'samplingPointIds' => $samplingPointIds,
            'interviewerIds'   => $interviewerIds
        ];

        return $this->surveyAssignmentEndpoint->massUnassign($this->surveyId, $data);
    }
}
