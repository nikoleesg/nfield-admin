<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAssignmentEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\InterviewerSamplingPointAssignmentModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointInterviewerAssignmentsModel;

class SamplingPointAssignmentService
{
    public function __construct(
        protected SamplingPointAssignmentEndpointInterface $samplingPointAssignmentEndpoint,
        protected readonly string $surveyId,
        protected readonly string $samplingPointId,
    ) {}

    /** @return Collection<int, InterviewerSamplingPointAssignmentModel> */
    public function listAssignments(): Collection
    {
        return InterviewerSamplingPointAssignmentModel::collect(
            $this->samplingPointAssignmentEndpoint->list($this->surveyId, $this->samplingPointId),
            Collection::class
        );
    }

    public function assignInterviewer(string $interviewerId): SamplingPointInterviewerAssignmentsModel
    {
        return SamplingPointInterviewerAssignmentsModel::from(
            $this->samplingPointAssignmentEndpoint->assign($this->surveyId, $this->samplingPointId, $interviewerId)
        );
    }

    public function unassignInterviewer(string $interviewerId): void
    {
        $this->samplingPointAssignmentEndpoint->unassign($this->surveyId, $this->samplingPointId, $interviewerId);
    }
}
