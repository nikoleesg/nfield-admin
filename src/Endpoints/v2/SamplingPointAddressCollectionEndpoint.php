<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressCollectionEndpointInterface;

class SamplingPointAddressCollectionEndpoint extends BaseEndpoint implements SamplingPointAddressCollectionEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/$this->version/surveys";
    }

    /**
     * @param string $surveyId
     * @param string $samplingPointId
     * @return array
     */
    public function list(string $surveyId, string $samplingPointId): array
    {
        return $this->find($surveyId, $samplingPointId);
    }

    /**
     * @param string $surveyId
     * @param string $samplingPointId
     * @param array $data
     * @return array
     */
    public function find(string $surveyId, string $samplingPointId, array $data = []): array
    {
        $url = $this->nestedResourcePath($surveyId, 'samplingPoints', $samplingPointId, 'addresses');

        return $this->httpClient->get($url, $data)->json();
    }

    /**
     * @param string $surveyId
     * @param string $samplingPointId
     * @param array $addressModel
     * @return array
     */
    public function create(string $surveyId, string $samplingPointId, array $addressModel): array
    {
        $url = $this->nestedResourcePath($surveyId, 'samplingPoints', $samplingPointId, 'addresses');

        return $this->httpClient->post($url, $addressModel)->json();
    }
}
