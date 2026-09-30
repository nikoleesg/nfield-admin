<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use DateTimeInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyWaveEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyWaveScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\Waves\WaveMinSuccessfulsBeforeAutoStartModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Waves\WaveStartDateModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Waves\WaveStopDateModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurveyWave;

/**
 * The settings of one wave, reached through
 * `NfieldManager::surveyWaves()->forWave($waveId)`: its minimum successfuls
 * before auto-start, and its start and stop dates. The API addresses these by
 * the wave id alone.
 *
 * A wave is a survey; manage it as one through
 * `NfieldManager::surveys()->forSurvey($waveId)`. Copying a wave needs its
 * parent survey: see {@see ParentSurveyWaveService}.
 *
 * Services mirror the endpoint naming: this pairs with SurveyWaveEndpoint.
 */
class SurveyWaveService implements SurveyWaveScopedInterface
{
    use ScopedToSurveyWave;

    public function __construct(
        protected SurveyWaveEndpointInterface $surveyWaveEndpoint,
    ) {}

    public function minSuccessfulsBeforeAutoStart(): WaveMinSuccessfulsBeforeAutoStartModel
    {
        return WaveMinSuccessfulsBeforeAutoStartModel::from(
            $this->surveyWaveEndpoint->getMinSuccessfulsBeforeAutoStart($this->getWaveId())
        );
    }

    public function updateMinSuccessfulsBeforeAutoStart(int $minSuccessfuls): void
    {
        $payload = (new WaveMinSuccessfulsBeforeAutoStartModel($minSuccessfuls))->toArray();

        $this->surveyWaveEndpoint->updateMinSuccessfulsBeforeAutoStart($this->getWaveId(), $payload);
    }

    public function deleteMinSuccessfulsBeforeAutoStart(): void
    {
        $this->surveyWaveEndpoint->deleteMinSuccessfulsBeforeAutoStart($this->getWaveId());
    }

    public function startDate(): WaveStartDateModel
    {
        return WaveStartDateModel::from($this->surveyWaveEndpoint->getStartDate($this->getWaveId()));
    }

    /**
     * Set the start date; null clears it.
     */
    public function updateStartDate(DateTimeInterface|string|null $startDate): void
    {
        $payload = WaveStartDateModel::from(['startDate' => $startDate])->toArray();

        $this->surveyWaveEndpoint->updateStartDate($this->getWaveId(), $payload);
    }

    public function stopDate(): WaveStopDateModel
    {
        return WaveStopDateModel::from($this->surveyWaveEndpoint->getStopDate($this->getWaveId()));
    }

    /**
     * Set the stop date; null clears it.
     */
    public function updateStopDate(DateTimeInterface|string|null $stopDate): void
    {
        $payload = WaveStopDateModel::from(['stopDate' => $stopDate])->toArray();

        $this->surveyWaveEndpoint->updateStopDate($this->getWaveId(), $payload);
    }
}
