<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleCollectionEndpoint as SurveySampleCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleResourceEndpoint as SurveySampleResourceEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpoint as SurveySampleEndpointInterface;

final class SurveySampleEndpoint extends BaseEndpoint implements SurveySampleCollectionEndpointInterface, SurveySampleResourceEndpointInterface, SurveySampleEndpointInterface
{
    private string $version = 'v2';

    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    /**
     * @param string $surveyId
     * @return string
     */
    public function all(string $surveyId): string
    {
        $uri = $this->subResourcePath($surveyId, 'sample');

        return $this->httpClient->get($uri)->body();
    }

    /**
     * @param string $surveyId
     * @param string $sampleData
     * @return array
     */
    public function upload(string $surveyId, string $sampleData): array
    {
        $uri = $this->subResourcePath($surveyId, 'sample');

        return $this->httpClient->postRaw($uri, $sampleData, '')->json();
    }

    /**
     * @param string $surveyId
     * @param array $sampleFilterModel
     * @return array
     */
    public function destroy(string $surveyId, array $sampleFilterModel): array
    {
        $uri = $this->subResourcePath($surveyId, 'sample');

        return $this->httpClient->destroy($uri, $sampleFilterModel)->json();
    }

    /**
     * @param string $surveyId
     * @param int $interviewId
     * @return string
     */
    public function get(string $surveyId, int $interviewId): string
    {
        $uri = $this->subResourceItemPath($surveyId, 'sample', $interviewId);

        return $this->httpClient->get($uri)->body();
    }

    /**
     * @param string $surveyId
     * @param array $surveyUpdateSampleRecordModel
     * @return array
     */
    public function update(string $surveyId, array $surveyUpdateSampleRecordModel): array
    {
        $uri = $this->subResourceActionPath($surveyId, 'sample', 'update');

        return $this->httpClient->put($uri, $surveyUpdateSampleRecordModel)->json();
    }

    /**
     * @param string $surveyId
     * @param array $sampleFilterModel
     * @return array
     */
    public function block(string $surveyId, array $sampleFilterModel): array
    {
        $uri = $this->subResourceActionPath($surveyId, 'sample', 'block');

        return $this->httpClient->put($uri, $sampleFilterModel)->json();
    }

    /**
     * @param string $surveyId
     * @param array $surveyCreateSampleColumnModel
     * @return array
     */
    public function create(string $surveyId, array $surveyCreateSampleColumnModel): array
    {
        $uri = $this->subResourceActionPath($surveyId, 'sample', 'create');

        return $this->httpClient->post($uri, $surveyCreateSampleColumnModel)->json();
    }

    /**
     * @param string $surveyId
     * @param array $sampleFilterModel
     * @return array
     */
    public function reset(string $surveyId, array $sampleFilterModel): array
    {
        $uri = $this->subResourceActionPath($surveyId, 'sample', 'reset');

        return $this->httpClient->put($uri, $sampleFilterModel)->json();
    }

    /**
     * @param string $surveyId
     * @param array $clearSurveySampleModel
     * @return array
     */
    public function clear(string $surveyId, array $clearSurveySampleModel): array
    {
        $uri = $this->subResourceActionPath($surveyId, 'sample', 'clear');

        return $this->httpClient->put($uri, $clearSurveySampleModel)->json();
    }

    /**
     * @param string $surveyId
     * @param string $fileName
     * @return array
     */
    public function download(string $surveyId, string $fileName): array
    {
        $uri = $this->resourceActionPath($surveyId, 'sampleDataDownload') . '/' . $fileName;

        return $this->httpClient->post($uri)->json();
    }

}
