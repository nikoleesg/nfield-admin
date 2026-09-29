<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ParentSurveyWaveCollectionEndpointInterface;

final class ParentSurveyWaveCollectionEndpoint extends BaseEndpoint implements ParentSurveyWaveCollectionEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/parentSurveys";
    }

    public function list(string $parentSurveyId, array $query = []): array
    {
        $uri = $this->subResourcePath($parentSurveyId, 'waves');

        // The spec marks this list OData, so accept the envelope too.
        return $this->unwrapList($this->httpClient->get($uri, $query)->json());
    }

    public function create(string $parentSurveyId, array $parentSurveyWaveCreateRequestModel): array
    {
        $uri = $this->subResourcePath($parentSurveyId, 'waves');

        return $this->httpClient->post($uri, $parentSurveyWaveCreateRequestModel)->json();
    }
}
