<?php


namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Illuminate\Support\Str;
use Spatie\LaravelData\DataCollection;
use Nikoleesg\NfieldAdmin\HttpClient;
use Nikoleesg\NfieldAdmin\Data\InterviewerAssignmentResponseData;

class InterviewerAssignmentsEndpoint
{
    protected string $resourcePath = 'v1/Interviewers/{interviewerId}/Assignments';

    protected HttpClient $httpClient;

    public function __construct(string $interviewerId)
    {
        $this->resourcePath = Str::replace('{interviewerId}', $interviewerId, $this->resourcePath);

        $this->httpClient = new HttpClient();
    }

    public function index(): DataCollection
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $interviewerAssignment = json_decode($response->body(), true);

        return InterviewerAssignmentResponseData::collection($interviewerAssignment);
    }

}
