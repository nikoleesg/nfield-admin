<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyVarFileEndpointInterface;

final class SurveyVarFileEndpoint extends BaseEndpoint implements SurveyVarFileEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function get(string $surveyId): array
    {
        $uri = $this->subResourcePath($surveyId, 'varFile');

        return $this->httpClient->get($uri)->json();
    }

    public function getVersion(string $surveyId, string $eTag): array
    {
        $uri = $this->subResourceItemPath($surveyId, 'varFile', $eTag);

        return $this->httpClient->get($uri)->json();
    }
}
