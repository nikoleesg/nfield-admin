<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyResourceUsageEndpointInterface;

/**
 * `/v2/surveyResources`: each survey's resource usage. Named for its payload
 * so it cannot be mistaken for the fluent SurveyResource.
 */
final class SurveyResourceUsageEndpoint extends BaseEndpoint implements SurveyResourceUsageEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveyResources";
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
}
