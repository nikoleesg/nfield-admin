<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySamplingMethodEndpointInterface;

final class SurveySamplingMethodEndpoint extends BaseEndpoint implements SurveySamplingMethodEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function get(string $surveyId): array
    {
        $uri = $this->subResourcePath($surveyId, 'samplingMethod');

        return $this->httpClient->get($uri)->json();
    }

    public function update(string $surveyId, array $data): void
    {
        $uri = $this->subResourcePath($surveyId, 'samplingMethod');

        $this->httpClient->patch($uri, $data);
    }
}
