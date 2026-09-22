<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointQuotaTargetsEndpointInterface;

final class SamplingPointQuotaTargetsEndpoint extends BaseEndpoint implements SamplingPointQuotaTargetsEndpointInterface
{
    protected function buildPath(): string
    {
        return "/$this->version/surveys";
    }

    public function list(string $surveyId, string $samplingPointId): array
    {
        $uri = $this->subResourceItemActionPath($surveyId, 'samplingPoints', $samplingPointId, 'quotaTargets');

        return $this->httpClient->get($uri)->json();
    }

    public function get(string $surveyId, string $samplingPointId, string $quotaLevelId): array
    {
        $uri = $this->nestedResourceItemPath($surveyId, 'samplingPoints', $samplingPointId, 'quotaTargets', $quotaLevelId);

        return $this->httpClient->get($uri)->json();
    }

    public function update(string $surveyId, string $samplingPointId, string $quotaLevelId, array $data): array
    {
        $uri = $this->nestedResourceItemPath($surveyId, 'samplingPoints', $samplingPointId, 'quotaTargets', $quotaLevelId);

        return $this->httpClient->patch($uri, $data)->json();
    }
}
