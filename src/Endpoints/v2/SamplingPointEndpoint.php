<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointEndpointInterface;

final class SamplingPointEndpoint extends BaseEndpoint implements SamplingPointEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/$this->version/surveys";
    }

    public function get(string $surveyId, string $samplingPointId): array
    {
        $uri = $this->subResourceItemPath($surveyId, 'samplingPoints', $samplingPointId);

        return $this->httpClient->get($uri)->json();
    }

    public function delete(string $surveyId, string $samplingPointId): void
    {
        $uri = $this->subResourceItemPath($surveyId, 'samplingPoints', $samplingPointId);

        $this->httpClient->delete($uri);
    }

    public function update(string $surveyId, string $samplingPointId, array $samplingPointUpdateRequestModel): array
    {
        // TODO: Implement update() method.

        return [];
    }

    public function activate(string $surveyId, string $samplingPointId): void
    {
        // TODO: Implement activate() method.
    }

    public function replace(string $surveyId, string $samplingPointId): void
    {
        // TODO: Implement replace() method.
    }
}
