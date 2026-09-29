<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyPerformanceEndpointInterface;

final class SurveyPerformanceEndpoint extends BaseEndpoint implements SurveyPerformanceEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function live(string $surveyId): array
    {
        $uri = $this->subResourceActionPath($surveyId, 'performance', 'metrics/live');

        return $this->httpClient->get($uri)->json();
    }

    public function test(string $surveyId): array
    {
        $uri = $this->subResourceActionPath($surveyId, 'performance', 'metrics/test');

        return $this->httpClient->get($uri)->json();
    }
}
