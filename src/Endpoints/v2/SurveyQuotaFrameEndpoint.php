<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaFrameEndpointInterface;

final class SurveyQuotaFrameEndpoint extends BaseEndpoint implements SurveyQuotaFrameEndpointInterface
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
}
