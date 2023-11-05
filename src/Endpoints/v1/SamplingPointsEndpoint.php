<?php


namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Nikoleesg\NfieldAdmin\HttpClient;
use Nikoleesg\NfieldAdmin\Data\SamplingPointData;

class SamplingPointsEndpoint
{
    protected string $resourcePath = 'v1/Surveys/{surveyId}/SamplingPoints';

    protected HttpClient $httpClient;

    public function __construct(string $surveyId)
    {
        $this->resourcePath = Str::replace('{surveyId}', $surveyId, $this->resourcePath);

        $this->httpClient = new HttpClient();
    }

    /**
     * Get a list of all sampling points for a survey.
     * @return Collection
     */
    public function getSamplingPoints(): Collection
    {
        $response = $this->httpClient->get($this->resourcePath);

        $samplingPoints = json_decode($response->body(), true);

        $samplingPointsCollection = collect();

        foreach ($samplingPoints as $samplingPoint)
        {
            $samplingPointsCollection->push(SamplingPointData::from($samplingPoint));
        }

        return $samplingPointsCollection;
    }

    /**
     * Get the details of a specific sampling point.
     * @param string $samplingPointId
     * @return SamplingPointData
     */
    public function getSamplingPoint(string $samplingPointId): SamplingPointData
    {
        $response = $this->httpClient->get($this->resourcePath . '/' . $samplingPointId);

        $samplingPoint = json_decode($response->body(), true);

        return SamplingPointData::from($samplingPoint);
    }

    /**
     * @param SamplingPointData $samplingPointData
     */
    public function createSamplingPoint(SamplingPointData $samplingPointData): SamplingPointData
    {
        $response = $this->httpClient->post($this->resourcePath, true, $samplingPointData->toArray());

        $samplingPoint = json_decode($response->body(), true);

        return SamplingPointData::from($samplingPoint);
    }

    /**
     * Returns the number of samplingPoints of the survey.
     * @return int
     */
    public function getCount(): int
    {
        $response = $this->httpClient->get($this->resourcePath . '/Count');

        return $response->body();
    }
}
