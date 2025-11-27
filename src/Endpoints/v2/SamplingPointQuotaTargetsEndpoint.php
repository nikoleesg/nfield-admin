<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;


use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointQuotaTargetsEndpointInterface;

class SamplingPointQuotaTargetsEndpoint extends BaseEndpoint implements SamplingPointQuotaTargetsEndpointInterface
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
        $url = $this->subResourceItemActionPath($surveyId, 'samplingPoints', $samplingPointId, 'quotaTargets');

        return $this->httpClient->get($url)->json();
    }

    /**
     * @param string $surveyId
     * @param string $samplingPointId
     * @param string $quotaLevelId
     * @return array
     */
    public function get(string $surveyId, string $samplingPointId, string $quotaLevelId): array
    {
        $url = $this->nestedResourceItemPath($surveyId, 'samplingPoints', $samplingPointId, 'quotaTargets', $quotaLevelId);

        return $this->httpClient->get($url)->json();
    }

    /**
     * @param string $surveyId
     * @param string $samplingPointId
     * @param string $quotaLevelId
     * @param array $data
     * @return array
     */
    public function update(string $surveyId, string $samplingPointId, string $quotaLevelId, array $data): array
    {
        $url = $this->nestedResourceItemPath($surveyId, 'samplingPoints', $samplingPointId, 'quotaTargets', $quotaLevelId);

        return $this->httpClient->patch($url, $data)->json();
    }
}
