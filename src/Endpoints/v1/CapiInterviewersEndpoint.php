<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Nikoleesg\NfieldAdmin\Data\AddressData;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Exceptions\CannotCreateData;
use Nikoleesg\NfieldAdmin\HttpClient;
use Nikoleesg\NfieldAdmin\Data\InterviewerDTO;
use Nikoleesg\NfieldAdmin\Data\NewCapiInterviewerRequestData;
use Nikoleesg\NfieldAdmin\Data\InterviewerDetailsData;

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
     *
     * @return DataCollection
     */
    public function index(): DataCollection
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $interviewers = json_decode($response->body(), true);

        return InterviewerDTO::collection($interviewers);
    }

    /**
     * This method retrieve details of a specific interviewer using the interviewerId.
     *
     * @param string $interviewerId
     * @return ?InterviewerDTO
     */
    public function show(string $interviewerId): ?InterviewerDTO
    {
        $resourcePath = $this->resourcePath . "/$interviewerId";

        $response = $this->httpClient->get($resourcePath);

        $interviewer = json_decode($response->body(), true);

        try {
            $interviewerData = InterviewerDTO::from($interviewer);
        } catch (CannotCreateData $exception) {
            $interviewerData = InterviewerDTO::optional(null);
        }

        return $interviewerData;
    }

    /**
     * This method creates a new interviewer.
     *
     * @param NewCapiInterviewerRequestData $data
     * @return InterviewerDTO|null
     */
    public function store(NewCapiInterviewerRequestData $data): ?InterviewerDTO
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->post($resourcePath, true, array_change_key_casing($data->toArray(), CASE_STUDLY));

        $interviewer = json_decode($response->body(), true);

        try {
            $interviewerData = InterviewerDTO::from($interviewer);
        } catch (CannotCreateData $exception) {
            $interviewerData = InterviewerDTO::optional(null);
        }

        return $interviewerData;
    }

    // This method deletes a specified interviewer.
    public function destroy(string $interviewerId)
    {
        $resourcePath = $this->resourcePath . "/$interviewerId";

        $response = $this->httpClient->delete($resourcePath);

        return empty($response->body());
    }

    // Update an interviewer with the specified specified fields
    public function update(string $interviewerId, string|array $data): mixed
    {
        $resourcePath = $this->resourcePath . "/$interviewerId";

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
