<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyCollectionEndpointInterface;

final class SurveyCollectionEndpoint extends BaseEndpoint implements SurveyCollectionEndpointInterface
{
    private string $version = 'v2';

    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function list(): array
    {
        $url = $this->basePath();

        return $this->httpClient->get($url)->json();
    }

    public function find(array $data): array
    {
        $url = $this->basePath();

        return $this->httpClient->get($url, $data)->json();
    }

    public function create(array $surveyModel): array
    {
        $url = $this->basePath();

        return $this->httpClient->post($url, $surveyModel)->json();
    }

    public function clone(array $surveyFromBlueprintModel): array
    {
        // TODO: Implement clone() method.
        return [];
    }

    public function search(string $value): array
    {
        $url = $this->actionPath('search');

        return $this->httpClient->get($url, ['Value' => $value])->json();
    }
}
