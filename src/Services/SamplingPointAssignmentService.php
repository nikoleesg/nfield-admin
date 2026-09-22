<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAssignmentEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SamplingPointScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\InterviewerSamplingPointAssignmentModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointInterviewerAssignmentsModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSamplingPoint;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SamplingPointAssignmentService implements SamplingPointScopedInterface
{
    use ScopedToSamplingPoint;
    use ScopedToSurvey;

    public function __construct(
        protected SamplingPointAssignmentEndpointInterface $samplingPointAssignmentEndpoint,
    ) {}

    /** @return Collection<int, InterviewerSamplingPointAssignmentModel> */
    public function listAssignments(): Collection
    {
        return InterviewerSamplingPointAssignmentModel::collect(
            $this->samplingPointAssignmentEndpoint->list($this->getSurveyId(), $this->getSamplingPointId()),
            Collection::class
        );
    }

    public function assignInterviewer(string $interviewerId): SamplingPointInterviewerAssignmentsModel
    {
        return SamplingPointInterviewerAssignmentsModel::from(
            $this->samplingPointAssignmentEndpoint->assign($this->getSurveyId(), $this->getSamplingPointId(), $interviewerId)
        );
    }

    public function unassignInterviewer(string $interviewerId): void
    {
        $this->samplingPointAssignmentEndpoint->unassign($this->getSurveyId(), $this->getSamplingPointId(), $interviewerId);
    }
}
