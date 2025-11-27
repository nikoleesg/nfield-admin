<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;


use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyFieldworkEndpointInterface;

final class SurveyFieldworkEndpoint extends BaseEndpoint implements SurveyFieldworkEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function start(string $surveyId): bool
    {
        $uri = $this->subResourceActionPath($surveyId, 'fieldwork', 'start');

        return $this->httpClient->put($uri)->getStatusCode() === 200;
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

    public function stop(string $surveyId, array $surveysFieldworkStopRequestModel): bool
    {
        $uri  = $this->subResourceActionPath($surveyId, 'fieldwork', 'stop');

        return $this->httpClient->put($uri, $surveysFieldworkStopRequestModel)->getStatusCode() === 204;
    }
}
