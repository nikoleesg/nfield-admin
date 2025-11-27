<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpointInterface;

final class SurveyEndpoint extends BaseEndpoint implements SurveyEndpointInterface
{
    private string $version = 'v2';

    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function get(string $surveyId): array
    {
        $uri = $this->resourcePath($surveyId);

        return $this->httpClient->get($uri)->json();
    }

    public function destroy(string $surveyId): void
    {
        $uri = $this->resourcePath($surveyId);

        $this->httpClient->delete($uri)->json();
    }

    public function updatePartial(string $surveyId, array $surveyUpdateModel): array
    {
        $uri = $this->resourcePath($surveyId);

        return $this->httpClient->patch($uri, $surveyUpdateModel)->json();
    }

    public function counts(string $surveyId): array
    {
        $uri = $this->resourceActionPath($surveyId, 'counts');

        return $this->httpClient->get($uri)->json();
    }

    public function getCustomColumns(string $surveyId): array
    {
        $uri = $this->resourceActionPath($surveyId, 'customColumns');

        return $this->httpClient->get($uri)->json();
    }

}
