<?php


namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Nikoleesg\NfieldAdmin\HttpClient;
use Nikoleesg\NfieldAdmin\Data\SamplingPointData;

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
     * @return Collection
     */
    public function getSurveys(): Collection
    {
        $response = $this->httpClient->get($this->resourcePath);

        $surveys = json_decode($response->body(), true);

        $surveysCollection = collect();

        foreach ($surveys as $survey)
        {
            $surveysCollection->push(SamplingPointData::from($survey));
        }

        return $surveysCollection;
    }

    /**
     * This method retrieve details of a specific survey.
     * @param string $surveyId
     * @return SamplingPointData
     */
    public function getSurvey(string $surveyId): SamplingPointData
    {
        $response = $this->httpClient->get($this->resourcePath . '/' . $surveyId);

        $survey = json_decode($response->body(), true);

        return SamplingPointData::from($survey);
    }
}
