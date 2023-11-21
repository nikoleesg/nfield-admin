<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Exceptions\CannotCreateData;
use Nikoleesg\NfieldAdmin\HttpClient;
use Nikoleesg\NfieldAdmin\Endpoints\BaseEndpoint;
use Nikoleesg\NfieldAdmin\Data\InterviewerSamplingPointAssignmentData as InterviewerAssignmentData;
use Nikoleesg\NfieldAdmin\Data\SamplingPointInterviewerAssignmentsData as InterviewerAssignmentsData;


class SamplingPointsAssignmentsEndpoint extends BaseEndpoint
{
    protected string $resourcePath = 'v1/Surveys/{surveyId}/SamplingPointsAssignments';

    protected ?string $surveyId;

    public function __construct(?string $surveyId = null)
    {
        $this->resourcePath = str_replace(['{surveyId}'], [$surveyId], $this->resourcePath);

        parent::__construct();
    }

    public function store(InterviewerAssignmentsData $interviewerAssignmentsData): ?InterviewerAssignmentsData
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->post($resourcePath, true, array_change_key_casing($interviewerAssignmentsData->toArray(), CASE_STUDLY));

        $interviewerAssignments = json_decode($response->body(), true);

        try {
            $assignmentsData = InterviewerAssignmentsData::from($interviewerAssignments);
        } catch (CannotCreateData $exception) {
            $assignmentsData = InterviewerAssignmentsData::optional(null);
        }

        return $assignmentsData;
    }

    public function destroy(InterviewerAssignmentsData $interviewerAssignmentsData)
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->delete($resourcePath, true, array_change_key_casing($interviewerAssignmentsData->toArray(), CASE_STUDLY));

        return $response->noContent();
    }
}
