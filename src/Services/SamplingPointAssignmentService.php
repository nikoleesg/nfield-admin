<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAssignmentEndpointInterface;

class SamplingPointAssignmentService
{
    public function __construct(
        protected SamplingPointAssignmentEndpointInterface $samplingPointAssignmentEndpoint,
        protected readonly string $surveyId,
        protected readonly string $samplingPointId,
    ) {}

    public function listAssignments(): array
    {
        return $this->samplingPointAssignmentEndpoint->list($this->surveyId, $this->samplingPointId);
    }

    public function assignInterviewer(string $interviewerId): array
    {
        return $this->samplingPointAssignmentEndpoint->assign($this->surveyId, $this->samplingPointId, $interviewerId);
    }

    public function unassignInterviewer(string $interviewerId): void
    {
        $this->samplingPointAssignmentEndpoint->unassign($this->surveyId, $this->samplingPointId, $interviewerId);
    }
}
