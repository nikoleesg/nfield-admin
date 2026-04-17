<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyPublicIdsEndpointInterface;

final class SurveyPublicIdsEndpoint extends BaseEndpoint implements SurveyPublicIdsEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function list(string $surveyId): array
    {
        $uri = $this->subResourcePath($surveyId, 'publicIds');

        return $this->httpClient->get($uri)->json();
    }

    public function update(string $surveyId, array $models): void
    {
        $uri = $this->subResourcePath($surveyId, 'publicIds');

        $this->httpClient->put($uri, $models);
    }
}
