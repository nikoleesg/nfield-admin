<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ParentSurveyEndpointInterface;

final class ParentSurveyEndpoint extends BaseEndpoint implements ParentSurveyEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/parentSurveys";
    }

    public function getCheckMinSuccessfulsBeforeAutoStart(string $parentSurveyId): array
    {
        $uri = $this->resourceActionPath($parentSurveyId, 'checkMinSuccessfulsBeforeAutoStart');

        return $this->httpClient->get($uri)->json();
    }

    public function updateCheckMinSuccessfulsBeforeAutoStart(string $parentSurveyId, array $waveCheckMinSuccessfulsBeforeAutoStartModel): void
    {
        $uri = $this->resourceActionPath($parentSurveyId, 'checkMinSuccessfulsBeforeAutoStart');

        $this->httpClient->put($uri, $waveCheckMinSuccessfulsBeforeAutoStartModel);
    }
}
