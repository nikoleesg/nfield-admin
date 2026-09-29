<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaVersionsEndpointInterface;

final class SurveyQuotaVersionsEndpoint extends BaseEndpoint implements SurveyQuotaVersionsEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function list(string $surveyId): array
    {
        $uri = $this->subResourcePath($surveyId, 'quotaVersions');

        return $this->httpClient->get($uri)->json();
    }

    public function get(string $surveyId, string $eTag): array
    {
        $uri = $this->subResourceItemPath($surveyId, 'quotaVersions', $eTag);

        return $this->httpClient->get($uri)->json();
    }
}
