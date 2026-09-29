<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ParentSurveyCollectionEndpointInterface;

final class ParentSurveyCollectionEndpoint extends BaseEndpoint implements ParentSurveyCollectionEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/parentSurveys";
    }

    public function list(array $query = []): array
    {
        $uri = $this->basePath();

        // The spec marks this list OData, so accept the envelope too.
        return $this->unwrapList($this->httpClient->get($uri, $query)->json());
    }

    public function create(array $parentSurveyCreateRequestModel): array
    {
        $uri = $this->basePath();

        return $this->httpClient->post($uri, $parentSurveyCreateRequestModel)->json();
    }
}
