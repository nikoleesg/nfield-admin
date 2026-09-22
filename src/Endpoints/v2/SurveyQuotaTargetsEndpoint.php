<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaTargetsEndpointInterface;

final class SurveyQuotaTargetsEndpoint extends BaseEndpoint implements SurveyQuotaTargetsEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function getQuotaTargets(string $surveyId): array
    {
        $uri = $this->subResourcePath($surveyId, 'quotaTargets');

        return $this->httpClient->get($uri)->json();
    }

    public function getQuotaTargetsByETag(string $surveyId, int $eTag): array
    {
        $uri = $this->subResourceItemPath($surveyId, 'quotaTargets', $eTag);

        return $this->httpClient->get($uri)->json();
    }
}
