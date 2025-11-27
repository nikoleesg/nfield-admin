<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;


use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressEndpointInterface;

class SamplingPointAddressEndpoint extends BaseEndpoint implements SamplingPointAddressEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/$this->version/surveys";
    }

    /**
     * @param string $surveyId
     * @param string $samplingPointId
     * @param string $addressId
     * @return array
     */
    public function get(string $surveyId, string $samplingPointId, string $addressId): array
    {
        $url = $this->nestedResourceItemPath($surveyId, 'samplingPoints', $samplingPointId, 'addresses', $addressId);

        return $this->httpClient->get($url)->json();
    }

    /**
     * @param string $surveyId
     * @param string $samplingPointId
     * @param string $addressId
     * @return bool
     */
    public function delete(string $surveyId, string $samplingPointId, string $addressId): bool
    {
        $url = $this->nestedResourceItemPath($surveyId, 'samplingPoints', $samplingPointId, 'addresses', $addressId);

        return $this->httpClient->delete($url)->getStatusCode() == 204;
    }
}
