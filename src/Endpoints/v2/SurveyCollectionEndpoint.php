<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyCollectionEndpointInterface;

final class SurveyCollectionEndpoint extends BaseEndpoint implements SurveyCollectionEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function list(): array
    {
        $uri = $this->basePath();

        return $this->httpClient->get($uri)->json();
    }

    public function find(array $data): array
    {
        $uri = $this->basePath();

        return $this->httpClient->get($uri, $data)->json();
    }

    public function create(array $surveyModel): array
    {
        $uri = $this->basePath();

        return $this->httpClient->post($uri, $surveyModel)->json();
    }

    public function createFromBlueprint(array $surveyFromBlueprintModel): array
    {
        $uri = $this->actionPath('createSurveyFromBlueprint');

        return $this->httpClient->post($uri, $surveyFromBlueprintModel)->json();
    }

    public function search(string $value): array
    {
        $uri = $this->actionPath('search');

        return $this->httpClient->get($uri, ['Value' => $value])->json();
    }
}
