<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;


use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleCollectionEndpointInterface;

class SurveySampleCollectionEndpoint extends BaseEndpoint implements SurveySampleCollectionEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/$this->version/surveys";
    }

    /**
     * @param string $surveyId
     * @return string
     */
    public function download(string $surveyId): string
    {
        $url = $this->subResourcePath($surveyId, 'sample');

        return $this->httpClient->get($url)->body();
    }

    /**
     * @param string $surveyId
     * @param string $sampleData
     * @return array
     */
    public function upload(string $surveyId, string $sampleData): array
    {
        $url = $this->subResourcePath($surveyId, 'sample');

        // TODO: check if set contentType to text/csv
        return $this->httpClient->postRaw($url, $sampleData, '')->json();
    }

    /**
     * @param string $surveyId
     * @param array $sampleFilterModel
     * @return array
     */
    public function block(string $surveyId, array $sampleFilterModel): array
    {
        $url = $this->subResourceActionPath($surveyId, 'sample', 'block');

        return $this->httpClient->put($url, $sampleFilterModel)->json();
    }

    /**
     * @param string $surveyId
     * @param array $surveyCreateSampleColumnModel
     * @return array
     */
    public function create(string $surveyId, array $surveyCreateSampleColumnModel): array
    {
        $url = $this->subResourceActionPath($surveyId, 'sample', 'create');

        return $this->httpClient->post($url, $surveyCreateSampleColumnModel)->json();
    }

    /**
     * @param string $surveyId
     * @param array $sampleFilterModel
     * @return array
     */
    public function reset(string $surveyId, array $sampleFilterModel): array
    {
        $url = $this->subResourceActionPath($surveyId, 'sample', 'reset');

        return $this->httpClient->put($url, $sampleFilterModel)->json();
    }

    /**
     * @param string $surveyId
     * @param array $clearSurveySampleModel
     * @return array
     */
    public function clear(string $surveyId, array $clearSurveySampleModel): array
    {
        $url = $this->subResourceActionPath($surveyId, 'sample', 'clear');

        return $this->httpClient->put($url, $clearSurveySampleModel)->json();
    }

    /**
     * @param string $surveyId
     * @param string $fileName
     * @return array
     */
    public function requestDownload(string $surveyId, string $fileName): array
    {
        $url = $this->resourceActionPath($surveyId, 'sampleDataDownload') . '/' . $fileName;

        return $this->httpClient->post($url)->json();
    }
}
