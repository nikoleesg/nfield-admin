<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaEndpointInterface;

class SurveyQuotaEndpoint extends BaseEndpoint implements SurveyQuotaEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    /**
     * @param string $surveyId
     * @return array
     */
    public function getQuotaFrame(string $surveyId): array
    {
        $url = $this->subResourcePath($surveyId, 'surveyQuotaFrame');

        return $this->httpClient->get($url)->json();
    }

    /**
     * @param string $surveyId
     * @param array $data
     * @return array
     */
    public function setQuotaFrame(string $surveyId, array $data): array
    {
        $url = $this->subResourcePath($surveyId, 'surveyQuotaFrame');

        return $this->httpClient->put($url, $data)->json();
    }

    /**
     * @param string $surveyId
     * @param string $eTag
     * @param array $data
     * @return array
     */
    public function setQuotaLevelsTargets(string $surveyId, string $eTag, array $data): array
    {
        $url = $this->subResourceItemPath($surveyId, 'surveyQuotaFrame', $eTag);

        return $this->httpClient->put($url, $data)->json();
    }

    /**
     * @param string $surveyId
     * @return array
     */
    public function getQuotaTargets(string $surveyId): array
    {
        $url = $this->subResourcePath($surveyId, 'quotaTargets');

        return $this->httpClient->get($url)->json();
    }

    /**
     * @param string $surveyId
     * @param int $eTag
     * @return array
     */
    public function getQuotaTargetsByETag(string $surveyId, int $eTag): array
    {
        $url = $this->subResourceItemPath($surveyId, 'quotaTargets', $eTag);

        return $this->httpClient->get($url)->json();
    }

    /**
     * @param string $surveyId
     * @return array
     */
    public function getQuotaVersions(string $surveyId): array
    {
        $url = $this->subResourcePath($surveyId, 'quotaVersions');

        return $this->httpClient->get($url)->json();
    }

    /**
     * @param string $surveyId
     * @param int $eTag
     * @return array
     */
    public function getQuotaVersionsByETag(string $surveyId, int $eTag): array
    {
        $url = $this->subResourceItemPath($surveyId, 'quotaVersions', $eTag);

        return $this->httpClient->get($url)->json();
    }


}
