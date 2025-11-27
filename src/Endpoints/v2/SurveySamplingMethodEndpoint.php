<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySamplingMethodEndpointInterface;

class SurveySamplingMethodEndpoint extends BaseEndpoint implements SurveySamplingMethodEndpointInterface
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
    public function get(string $surveyId): array
    {
        $url = $this->subResourcePath($surveyId, 'samplingMethod');

        return $this->httpClient->get($url)->json();
    }

    /**
     * @param string $surveyId
     * @param array $data
     * @return bool
     */
    public function update(string $surveyId, array $data): bool
    {
        $url = $this->subResourcePath($surveyId, 'samplingMethod');

        return $this->httpClient->patch($url, $data)->getStatusCode() === 200;
    }
}
