<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyCollectionEndpoint as SurveyCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyResourceEndpoint as SurveyResourceEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpoint as SurveyEndpointInterface;

final class SurveyEndpoint extends BaseEndpoint implements SurveyEndpointInterface
{
    private string $version = 'v2';

    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function all(): array
    {
        $uri = $this->basePath();

        return $this->httpClient->get($uri)->json();
    }

    public function filter(array $query): array
    {
        $uri = $this->basePath();

        return $this->httpClient->get($uri, $query)->json();
    }

    public function create(array $surveyModel): array
    {
        $uri = $this->basePath();

        return $this->httpClient->post($uri, $surveyModel)->json();
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

    public function search(string $value): array
    {
        $uri = $this->actionPath('search');

        return $this->httpClient->get($uri, ['Value' => $value])->json();
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

    public function requestDataDownload(string $surveyId, array $surveyDataRequestModel): array
    {
        $uri = $this->resourceActionPath($surveyId, 'dataDownload');

        return $this->httpClient->post($uri, $surveyDataRequestModel)->json();
    }
}
