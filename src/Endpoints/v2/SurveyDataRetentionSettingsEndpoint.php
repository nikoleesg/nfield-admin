<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyDataRetentionSettingsEndpointInterface;

final class SurveyDataRetentionSettingsEndpoint extends BaseEndpoint implements SurveyDataRetentionSettingsEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function get(string $surveyId): array
    {
        $uri = $this->subResourcePath($surveyId, 'dataRetentionSettings');

        return $this->httpClient->get($uri)->json();
    }

    public function update(string $surveyId, array $updateDataRetentionSettingsModel): void
    {
        $uri = $this->subResourcePath($surveyId, 'dataRetentionSettings');

        $this->httpClient->put($uri, $updateDataRetentionSettingsModel);
    }
}
