<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGroupCollectionEndpointInterface;

final class SurveyGroupCollectionEndpoint extends BaseEndpoint implements SurveyGroupCollectionEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveyGroups";
    }

    public function list(): array
    {
        $uri = $this->basePath();

        return $this->httpClient->get($uri)->json();
    }
}
