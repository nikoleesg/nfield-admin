<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Nikoleesg\NfieldAdmin\Data\SurveyData;
use Nikoleesg\NfieldAdmin\HttpClient;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Exceptions\CannotCreateData;

class SurveysEndpoint
{
    protected string $resourcePath = 'v1/Surveys';

    protected HttpClient $httpClient;

    public function __construct()
    {
        $this->httpClient = new HttpClient();
    }

    /**
     * This method retrieves a list of surveys.
     * https://apiap.nfieldmr.com/help/api/get-v1-surveys
     */
    public function index(): DataCollection
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $surveys = json_decode($response->body(), true);

        return SurveyData::collection($surveys);
    }

    /**
     * This method retrieve details of a specific survey.
     * Supports blueprint surveys.
     * https://apiap.nfieldmr.com/help/api/get-v1-surveys-surveyid
     */
    public function show(string $surveyId): ?SurveyData
    {
        $resourcePath = $this->resourcePath."/$surveyId";

        $response = $this->httpClient->get($resourcePath);

        $survey = json_decode($response->body(), true);

        try {
            $surveyData = SurveyData::from($survey);

        } catch (CannotCreateData $exception) {
            $surveyData = SurveyData::optional(null);
        }

        return $surveyData;
    }

    /**
     * This method creates a new survey.
     * Supports blueprint surveys.
     * https://apiap.nfieldmr.com/help/api/post-v1-surveys
     */
    public function store(SurveyData $surveyData): SurveyData
    {
        $resourcePath = $this->resourcePath;

        // Transform to Nfield API SurveyModel
        $surveyModel = array_change_key_casing($surveyData->toArray(), CASE_STUDLY);

        $response = $this->httpClient->post($resourcePath, true, $surveyModel);

        $survey = json_decode($response->body(), true);

        return SurveyData::from($survey);
    }

    /**
     * This method deletes a specified survey.
     * Supports blueprint surveys.
     * https://apiap.nfieldmr.com/help/api/delete-v1-surveys-surveyid
     */
    public function destroy(string $surveyId): bool
    {
        $resourcePath = $this->resourcePath."/$surveyId";

        $response = $this->httpClient->delete($resourcePath);

        return $response->noContent();
    }

    /**
     * Update a survey with the specified fields.
     * Supports blueprint surveys.
     * https://apiap.nfieldmr.com/help/api/patch-v1-surveys-surveyid
     */
    public function update(string $surveyId, array $properties): SurveyData
    {
        $resourcePath = $this->resourcePath."/$surveyId";

        $response = $this->httpClient->patch($resourcePath, true, $properties);

        $survey = json_decode($response->body(), true);

        return SurveyData::from($survey);
    }
}
