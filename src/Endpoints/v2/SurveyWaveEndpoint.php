<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyWaveEndpointInterface;

/**
 * `/v2/surveyWaves/{waveId}/...`: settings of one wave, addressed by the wave (survey) id alone.
 */
final class SurveyWaveEndpoint extends BaseEndpoint implements SurveyWaveEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveyWaves";
    }

    public function getMinSuccessfulsBeforeAutoStart(string $waveId): array
    {
        $uri = $this->resourceActionPath($waveId, 'minSuccessfulsBeforeAutoStart');

        return $this->httpClient->get($uri)->json();
    }

    public function updateMinSuccessfulsBeforeAutoStart(string $waveId, array $waveMinSuccessfulsBeforeAutoStartModel): void
    {
        $uri = $this->resourceActionPath($waveId, 'minSuccessfulsBeforeAutoStart');

        $this->httpClient->put($uri, $waveMinSuccessfulsBeforeAutoStartModel);
    }

    public function deleteMinSuccessfulsBeforeAutoStart(string $waveId): void
    {
        $uri = $this->resourceActionPath($waveId, 'minSuccessfulsBeforeAutoStart');

        $this->httpClient->delete($uri);
    }

    public function getStartDate(string $waveId): array
    {
        $uri = $this->resourceActionPath($waveId, 'startDate');

        return $this->httpClient->get($uri)->json();
    }

    public function updateStartDate(string $waveId, array $waveStartDateModel): void
    {
        $uri = $this->resourceActionPath($waveId, 'startDate');

        $this->httpClient->put($uri, $waveStartDateModel);
    }

    public function getStopDate(string $waveId): array
    {
        $uri = $this->resourceActionPath($waveId, 'stopDate');

        return $this->httpClient->get($uri)->json();
    }

    public function updateStopDate(string $waveId, array $waveStopDateModel): void
    {
        $uri = $this->resourceActionPath($waveId, 'stopDate');

        $this->httpClient->put($uri, $waveStopDateModel);
    }
}
