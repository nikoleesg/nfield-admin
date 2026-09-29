<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ParentSurveyWaveEndpointInterface;

final class ParentSurveyWaveEndpoint extends BaseEndpoint implements ParentSurveyWaveEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/parentSurveys";
    }

    public function copy(string $parentSurveyId, string $waveId, array $parentSurveyWaveCopyRequestModel): array
    {
        $uri = $this->subResourceItemPath($parentSurveyId, 'waves', $waveId);

        return $this->httpClient->post($uri, $parentSurveyWaveCopyRequestModel)->json();
    }
}
