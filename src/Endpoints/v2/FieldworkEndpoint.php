<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\FieldworkEndpoint as FieldworkEndpointInterface;

final class FieldworkEndpoint extends BaseEndpoint implements FieldworkEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function start(string $surveyId): void
    {
        $uri = $this->subResourceActionPath($surveyId, 'fieldwork', 'start');

        $this->httpClient->put($uri);
    }

    public function status(string $surveyId): int
    {
        $uri  = $this->subResourceActionPath($surveyId, 'fieldwork', 'status');

        return $this->httpClient->get($uri)->json();
    }

    public function counts(string $surveyId): array
    {
        $uri  = $this->subResourceActionPath($surveyId, 'fieldwork', 'counts');

        return $this->httpClient->get($uri)->json();
    }

    public function stop(string $surveyId, array $surveysFieldworkStopRequestModel): void
    {
        $uri  = $this->subResourceActionPath($surveyId, 'fieldwork', 'stop');

        $this->httpClient->put($uri, $surveysFieldworkStopRequestModel);
    }
}
