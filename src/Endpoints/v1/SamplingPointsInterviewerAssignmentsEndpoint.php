<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Exceptions\CannotCreateData;
use Nikoleesg\NfieldAdmin\HttpClient;
use Nikoleesg\NfieldAdmin\Endpoints\BaseEndpoint;
use Nikoleesg\NfieldAdmin\Data\InterviewerSamplingPointAssignmentData as InterviewerAssignmentData;
use Nikoleesg\NfieldAdmin\Data\SamplingPointInterviewerAssignmentsData as InterviewerAssignmentsData;


class SamplingPointsInterviewerAssignmentsEndpoint extends BaseEndpoint
{
    protected string $resourcePath = 'v1/Surveys/{surveyId}/SamplingPoints/{samplingPointId}/Assignments';

    protected ?string $surveyId;

    protected ?string $samplingPointId;

    public function __construct(?string $surveyId = null, ?string $samplingPointId = null)
    {
        $this->resourcePath = str_replace(['{surveyId}', '{samplingPointId}'], [$surveyId, $samplingPointId], $this->resourcePath);

        parent::__construct();
    }

    public function index(): DataCollection
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $interviewerAssignments = json_decode($response->body(), true);

        return InterviewerAssignmentData::collection($interviewerAssignments);
    }

    public function store(string $interviewerId): InterviewerAssignmentsData
    {
        $resourcePath = $this->resourcePath . "/$interviewerId";

        $response = $this->httpClient->post($resourcePath);

        $interviewerAssignments = json_decode($response->body(), true);

        try {
            $interviewerAssignmentsData = InterviewerAssignmentsData::from($interviewerAssignments);
        } catch (CannotCreateData $exception) {
            $interviewerAssignmentsData = InterviewerAssignmentsData::optional(null);
        }
        return $interviewerAssignmentsData;
    }

    public function destroy(string $interviewerId): bool
    {
        $resourcePath = $this->resourcePath . "/$interviewerId";

        $response = $this->httpClient->delete($resourcePath);

        return $response->noContent();
    }

}
