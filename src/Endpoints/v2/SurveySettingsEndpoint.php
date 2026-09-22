<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySettingsEndpointInterface;

final class SurveySettingsEndpoint extends BaseEndpoint implements SurveySettingsEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function listSettings(string $surveyId): array
    {
        $uri = $this->subResourcePath($surveyId, 'settings');

        return $this->httpClient->get($uri)->json();
    }

    public function addOrUpdateSetting(string $surveyId, array $setting): array
    {
        $uri = $this->subResourcePath($surveyId, 'settings');

        return $this->httpClient->post($uri, $setting)->json();
    }
}
