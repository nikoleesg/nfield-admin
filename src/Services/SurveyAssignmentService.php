<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySamplingPointsAssignmentsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointInterviewerAssignmentsModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SurveyAssignmentService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveySamplingPointsAssignmentsEndpointInterface $surveySamplingPointsAssignmentsEndpoint,
    ) {}

    /**
     * @param  array<int, string>  $samplingPointIds
     * @param  array<int, string>  $interviewerIds
     */
    public function assignInterviewers(array $samplingPointIds, array $interviewerIds): SamplingPointInterviewerAssignmentsModel
    {
        $payload = new SamplingPointInterviewerAssignmentsModel($samplingPointIds, $interviewerIds);

        return SamplingPointInterviewerAssignmentsModel::from(
            $this->surveySamplingPointsAssignmentsEndpoint->massAssign($this->getSurveyId(), $payload->toArray())
        );
    }

    /**
     * @param  array<int, string>  $samplingPointIds
     * @param  array<int, string>  $interviewerIds
     */
    public function unassignInterviewers(array $samplingPointIds, array $interviewerIds): void
    {
        $payload = new SamplingPointInterviewerAssignmentsModel($samplingPointIds, $interviewerIds);

        $this->surveySamplingPointsAssignmentsEndpoint->massUnassign($this->getSurveyId(), $payload->toArray());
    }
}
