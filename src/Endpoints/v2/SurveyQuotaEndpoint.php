<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaEndpointInterface;

final class SurveyQuotaEndpoint extends BaseEndpoint implements SurveyQuotaEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function getQuotaFrame(string $surveyId): array
    {
        $uri = $this->subResourcePath($surveyId, 'surveyQuotaFrame');

        return $this->httpClient->get($uri)->json();
    }

    public function setQuotaFrame(string $surveyId, array $data): array
    {
        $uri = $this->subResourcePath($surveyId, 'surveyQuotaFrame');

        return $this->httpClient->put($uri, $data)->json();
    }

    public function setQuotaLevelsTargets(string $surveyId, string $eTag, array $data): array
    {
        $uri = $this->subResourceItemPath($surveyId, 'surveyQuotaFrame', $eTag);

        return $this->httpClient->put($uri, $data)->json();
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

    public function getQuotaVersions(string $surveyId): array
    {
        $uri = $this->subResourcePath($surveyId, 'quotaVersions');

        return $this->httpClient->get($uri)->json();
    }

    public function getQuotaVersionsByETag(string $surveyId, int $eTag): array
    {
        $uri = $this->subResourceItemPath($surveyId, 'quotaVersions', $eTag);

        return $this->httpClient->get($uri)->json();
    }
}
