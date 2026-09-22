<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointEndpointInterface;

final class SamplingPointEndpoint extends BaseEndpoint implements SamplingPointEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
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
        $uri = $this->subResourceItemPath($surveyId, 'samplingPoints', $samplingPointId);

        return $this->httpClient->patch($uri, $samplingPointUpdateRequestModel)->json();
    }

    public function activate(string $surveyId, string $samplingPointId, array $activateRequestModel = []): array
    {
        $uri = $this->subResourceItemActionPath($surveyId, 'samplingPoints', $samplingPointId, 'activate');

        return $this->httpClient->patch($uri, $activateRequestModel)->json();
    }

    public function replace(string $surveyId, string $samplingPointId, array $replaceRequestModel): array
    {
        $uri = $this->subResourceItemActionPath($surveyId, 'samplingPoints', $samplingPointId, 'replace');

        return $this->httpClient->patch($uri, $replaceRequestModel)->json();
    }
}
