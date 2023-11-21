<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Nikoleesg\NfieldAdmin\Data\InterviewerData;
use Nikoleesg\NfieldAdmin\Data\InterviewerDetailsData;
use Nikoleesg\NfieldAdmin\Data\NewCapiInterviewerRequestData;
use Nikoleesg\NfieldAdmin\HttpClient;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Exceptions\CannotCreateData;

class CapiInterviewersEndpoint
{
    protected string $resourcePath = 'v1/CapiInterviewers';

    protected HttpClient $httpClient;

    public function __construct()
    {
        $this->httpClient = new HttpClient();
    }

    /**
     * This method retrieve a list of interviewers.
     */
    public function index(): DataCollection
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $interviewers = json_decode($response->body(), true);

        return InterviewerData::collection($interviewers);
    }

    /**
     * This method retrieve details of a specific interviewer using the interviewerId.
     *
     * @return ?InterviewerData
     */
    public function show(string $interviewerId): ?InterviewerData
    {
        $resourcePath = $this->resourcePath."/$interviewerId";

        $response = $this->httpClient->get($resourcePath);

        $interviewer = json_decode($response->body(), true);

        try {
            $interviewerData = InterviewerData::from($interviewer);
        } catch (CannotCreateData $exception) {
            $interviewerData = InterviewerData::optional(null);
        }

        return $interviewerData;
    }

    /**
     * This method creates a new interviewer.
     */
    public function store(NewCapiInterviewerRequestData $data): ?InterviewerData
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->post($resourcePath, true, array_change_key_casing($data->toArray(), CASE_STUDLY));

        $interviewer = json_decode($response->body(), true);

        try {
            $interviewerData = InterviewerData::from($interviewer);
        } catch (CannotCreateData $exception) {
            $interviewerData = InterviewerData::optional(null);
        }

        return $interviewerData;
    }

    // This method deletes a specified interviewer.
    public function destroy(string $interviewerId)
    {
        $resourcePath = $this->resourcePath."/$interviewerId";

        $response = $this->httpClient->delete($resourcePath);

        return empty($response->body());
    }

    // Update an interviewer with the specified specified fields
    public function update(string $interviewerId, string|array $data): mixed
    {
        $resourcePath = $this->resourcePath."/$interviewerId";

        if (is_string($data)) {
            $response = $this->httpClient->put($resourcePath, true, ['Password' => $data]);
        } else {
            $response = $this->httpClient->patch($resourcePath, true, array_change_key_casing($data, CASE_STUDLY));
        }

        $interviewer = json_decode($response->body(), true);

        try {
            $interviewerData = InterviewerDetailsData::from($interviewer);
        } catch (CannotCreateData $exception) {
            $interviewerData = InterviewerDetailsData::optional(null);
        }

        return $interviewerData;
    }
}
