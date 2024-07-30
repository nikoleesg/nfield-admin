<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Exceptions\CannotCreateData;
use Nikoleesg\NfieldAdmin\Endpoints\BaseEndpoint;
use Nikoleesg\NfieldAdmin\Data\SurveyResponseCodeDTO;
use Nikoleesg\NfieldAdmin\Data\SurveyResponseCodeForPatch;

class SurveyResponseCodesEndpoint extends BaseEndpoint
{
    protected string $resourcePath = 'v1/Surveys/{surveyId}/ResponseCodes';

    protected ?string $surveyId;

    public function __construct(?string $surveyId = null)
    {
        $this->resourcePath = str_replace(['{surveyId}'], [$surveyId], $this->resourcePath);

        parent::__construct();
    }

    /**
     * Retrieves a list of response codes based on a survey.
     *
     * @return DataCollection
     */
    public function index(): DataCollection
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $responseCodes = json_decode($response->body(), true);

        return SurveyResponseCodeDTO::collection($responseCodes);
    }

    /**
     * Retrieve details of a specific response code for a specific survey.
     *
     * @param int $responseCode
     * @return SurveyResponseCodeDTO|null
     */
    public function show(int $responseCode): ?SurveyResponseCodeDTO
    {
        $resourcePath = $this->resourcePath . "/$responseCode";

        $response = $this->httpClient->get($resourcePath);

        $responseCode = json_decode($response->body(), true);

        try {
            $responseCodeData = SurveyResponseCodeDTO::from($responseCode);
        } catch (CannotCreateData $exception) {
            $responseCodeData = SurveyResponseCodeDTO::optional(null);
        }

        return $responseCodeData;
    }

    /**
     * Deletes a specified response code. (Supports blueprint surveys)
     *
     * @param int $responseCode The response code that is to be deleted
     * @return int
     */
    public function delete(int $responseCode): int
    {
        $resourcePath = $this->resourcePath . "/$responseCode";

        $response = $this->httpClient->delete($resourcePath);

        return $response->status();
    }

    /**
     * Update a response code with the specified fields. (Supports blueprint surveys)
     *
     * @param int $responseCode The response code that is updated.
     * @param SurveyResponseCodeForPatch $responseCodeForPatch
     * @return int
     */
    public function update(int $responseCode, SurveyResponseCodeForPatch $responseCodeForPatch): int
    {
        $resourcePath = $this->resourcePath . "/$responseCode";

        $response = $this->httpClient->patch($resourcePath, true, $responseCodeForPatch->toArray());

        return $response->status();
    }

    /**
     * This method creates a new response code. (Supports blueprint surveys)
     *
     * @param SurveyResponseCodeDTO $surveyResponseCode
     * @return int
     */
    public function create(SurveyResponseCodeDTO $surveyResponseCode): int
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->post($resourcePath, true, $surveyResponseCode->toArray());

        return $response->status();
    }
}
