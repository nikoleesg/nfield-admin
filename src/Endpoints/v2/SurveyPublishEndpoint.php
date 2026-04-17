<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyPublishEndpointInterface;

final class SurveyPublishEndpoint extends BaseEndpoint implements SurveyPublishEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function getPublishState(string $surveyId): array
    {
        $uri = $this->subResourcePath($surveyId, 'publish');

        return $this->httpClient->get($uri)->json();
    }

    public function publish(string $surveyId, array $model): bool
    {
        $uri = $this->subResourcePath($surveyId, 'publish');

        return $this->httpClient->put($uri, $model)->successful();
    }

    public function startPublish(string $surveyId, array $model): array
    {
        $uri = $this->subResourceActionPath($surveyId, 'publish', 'start');

        return $this->httpClient->post($uri, $model)->json();
    }
}
