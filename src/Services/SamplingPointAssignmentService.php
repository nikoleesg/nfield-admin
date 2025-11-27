<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAssignmentEndpointInterface;

class SamplingPointAssignmentService
{
    protected ?string $surveyId = null;
    protected ?string $samplingPointId = null;

    public function __construct(
        protected SamplingPointAssignmentEndpointInterface $samplingPointAssignmentEndpoint
    ) {}

    public function setSurveyId(string $surveyId): self
    {
        $this->surveyId = $surveyId;
        return $this;
    }

    public function setSamplingPointId(string $samplingPointId): self
    {
        $this->samplingPointId = $samplingPointId;
        return $this;
    }

    public function listAssignments(): array
    {
        return $this->samplingPointAssignmentEndpoint->list($this->surveyId, $this->samplingPointId);
    }

    public function assignInterviewer(string $interviewerId): array
    {
        return $this->samplingPointAssignmentEndpoint->assign($this->surveyId, $this->samplingPointId, $interviewerId);
    }

    public function unassignInterviewer(string $interviewerId): bool
    {
        return $this->samplingPointAssignmentEndpoint->unassign($this->surveyId, $this->samplingPointId, $interviewerId);
    }

}
