<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySettingsEndpointInterface;
use Nikoleesg\NfieldAdmin\Endpoints\v2\BaseEndpoint;

final class SurveySettingsEndpoint extends BaseEndpoint implements SurveySettingsEndpointInterface
{
    protected function buildPath(): string
    {
        return '/v1/surveys';
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

    public function getGeneralSettings(string $surveyId): array
    {
        $uri = $this->subResourcePath($surveyId, 'generalSettings');

        return $this->httpClient->get($uri)->json();
    }

    public function updateGeneralSettings(string $surveyId, array $data): void
    {
        $uri = $this->subResourcePath($surveyId, 'generalSettings');

        $this->httpClient->patch($uri, $data);
    }
}
