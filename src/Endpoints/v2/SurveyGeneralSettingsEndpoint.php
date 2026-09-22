<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGeneralSettingsEndpointInterface;

final class SurveyGeneralSettingsEndpoint extends BaseEndpoint implements SurveyGeneralSettingsEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
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
