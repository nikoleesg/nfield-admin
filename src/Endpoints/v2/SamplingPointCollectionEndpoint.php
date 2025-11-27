<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Resources\SamplingPointResource;

final class SamplingPointCollectionEndpoint extends BaseEndpoint implements SamplingPointCollectionEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/$this->version/surveys";
    }

    /**
     * @param string $surveyId
     * @return array
     */
    public function list(string $surveyId): array
    {
        return $this->find($surveyId);
    }

    /**
     * @param string $surveyId
     * @param array $data
     * @return array
     */
    public function find(string $surveyId, array $data = []): array
    {
        $url = $this->subResourcePath($surveyId, 'samplingPoints');

        return $this->httpClient->get($url, $data)->json();
    }

    /**
     * @param string $surveyId
     * @param array $samplingPointCreateRequestModel
     * @return array
     */
    public function create(string $surveyId, array $samplingPointCreateRequestModel): array
    {
        $url = $this->subResourcePath($surveyId, 'samplingPoints');

        return $this->httpClient->post($url, $samplingPointCreateRequestModel)->json();
    }

    /**
     * Activated a list of spare sampling points so they can be assigned.
     * @param string $surveyId
     * @param array $samplingPointIds
     * @return void
     */
    public function batchActivate(string $surveyId, array $samplingPointIds): void
    {
        // TODO: Implement batchActivate() method.
    }
}
