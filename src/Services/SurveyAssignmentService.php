<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySamplingPointsAssignmentsEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointInterviewerAssignmentsModel;

class SurveyAssignmentService
{
    public function __construct(
        protected SurveySamplingPointsAssignmentsEndpointInterface $surveySamplingPointsAssignmentsEndpoint,
        protected readonly string $surveyId,
    ) {}

    /**
     * @param  array<int, string>  $samplingPointIds
     * @param  array<int, string>  $interviewerIds
     */
    public function assignInterviewers(array $samplingPointIds, array $interviewerIds): SamplingPointInterviewerAssignmentsModel
    {
        $payload = new SamplingPointInterviewerAssignmentsModel($samplingPointIds, $interviewerIds);

        return SamplingPointInterviewerAssignmentsModel::from(
            $this->surveySamplingPointsAssignmentsEndpoint->massAssign($this->surveyId, $payload->toArray())
        );
    }

    /**
     * @param  array<int, string>  $samplingPointIds
     * @param  array<int, string>  $interviewerIds
     */
    public function unassignInterviewers(array $samplingPointIds, array $interviewerIds): void
    {
        $payload = new SamplingPointInterviewerAssignmentsModel($samplingPointIds, $interviewerIds);

        $this->surveySamplingPointsAssignmentsEndpoint->massUnassign($this->surveyId, $payload->toArray());
    }
}
