<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyVersionsEndpointInterface;

final class SurveyVersionsEndpoint extends BaseEndpoint implements SurveyVersionsEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function list(string $surveyId): array
    {
        $uri = $this->subResourcePath($surveyId, 'versions');

        return $this->httpClient->get($uri)->json();
    }
}
