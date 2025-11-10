<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaTargetsEndpointInterface;

final class SurveyQuotaTargetsEndpoint extends BaseEndpoint implements SurveyQuotaTargetsEndpointInterface
{
    private string $version = 'v2';

    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function getSurveyQuotaTarget(string $surveyId, ?int $eTag = null)
    {
        $uri = $eTag
            ? $this->subResourceItemPath($surveyId, 'quotaTargets', $eTag)
            : $this->subResourcePath($surveyId, 'quotaTargets');

        return $this->httpClient->get($uri)->json();
    }
}
