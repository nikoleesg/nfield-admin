<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyScriptEndpointInterface;

final class SurveyScriptEndpoint extends BaseEndpoint implements SurveyScriptEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function get(string $surveyId): array
    {
        $uri = $this->subResourcePath($surveyId, 'script');

        return $this->httpClient->get($uri)->json();
    }

    public function getVersion(string $surveyId, string $eTag): array
    {
        $uri = $this->subResourceItemPath($surveyId, 'script', $eTag);

        return $this->httpClient->get($uri)->json();
    }

    public function update(string $surveyId, array $surveySetScriptModel): array
    {
        $uri = $this->subResourcePath($surveyId, 'script');

        return $this->httpClient->post($uri, $surveySetScriptModel)->json();
    }
}
