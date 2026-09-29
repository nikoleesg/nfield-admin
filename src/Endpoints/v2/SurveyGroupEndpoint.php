<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGroupEndpointInterface;

final class SurveyGroupEndpoint extends BaseEndpoint implements SurveyGroupEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveyGroups";
    }

    public function get(int $surveyGroupId): array
    {
        $uri = $this->resourcePath((string) $surveyGroupId);

        return $this->httpClient->get($uri)->json();
    }
}
