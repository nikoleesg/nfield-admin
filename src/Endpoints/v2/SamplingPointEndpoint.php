<?php

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
        $url = $this->subResourceItemPath($surveyId, 'samplingPoints', $samplingPointId);

        return $this->httpClient->get($url)->json();
    }

    public function delete(string $surveyId, string $samplingPointId): bool
    {
        $url = $this->subResourceItemPath($surveyId, 'samplingPoints', $samplingPointId);

        return $this->httpClient->delete($url)->getStatusCode() == 204;
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
