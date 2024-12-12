<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Nikoleesg\NfieldAdmin\HttpClient;

class SamplingMethodEndpoint
{
    protected string $resourcePath = 'v1/surveys/{surveyId}/samplingMethod';

    protected HttpClient $httpClient;

    public function __construct(string $surveyId)
    {
        $this->resourcePath = Str::replace('{surveyId}', $surveyId, $this->resourcePath);

        $this->httpClient = new HttpClient();
    }

    /**
     * This method retrieve current SamplingMethod of a specific survey.
     * https://apiap.nfieldmr.com/swagger/index.html#/Surveys%20-%20Sampling%20Method/get_v1_surveys__surveyId__samplingMethod
     */
    public function show(): ?string
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $surveySamplingMethod = json_decode($response->body(), true);

        return Arr::get($surveySamplingMethod, 'SamplingMethod');
    }

    /**
     * This method for saving the SamplingMethod
     * https://apiap.nfieldmr.com/swagger/index.html#/Surveys%20-%20Sampling%20Method/patch_v1_surveys__surveyId__samplingMethod
     */
    public function update(string $samplingMethod): bool
    {
        $resourcePath = $this->resourcePath;

        $properties = [
            'SamplingMethod' => $samplingMethod
        ];

        $response = $this->httpClient->patch($resourcePath, true, $properties);

        return $response->successful();
    }
}
