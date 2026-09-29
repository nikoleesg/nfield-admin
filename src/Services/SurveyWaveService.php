<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use DateTimeInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ParentSurveyWaveEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyWaveEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\ParentSurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyWaveScopedInterface;
use Nikoleesg\NfieldAdmin\Data\ParentSurvey\ParentSurveyWaveCopyRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Waves\WaveMinSuccessfulsBeforeAutoStartModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Waves\WaveStartDateModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Waves\WaveStopDateModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToParentSurvey;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurveyWave;

/**
 * One wave, reached through `$parentSurvey->waves()->forWave($waveId)` or
 * directly through `NfieldManager::surveyWaves()->forWave($waveId)`.
 *
 * A wave is a survey; manage it as one through
 * `NfieldManager::surveys()->forSurvey($waveId)`. This service holds what is
 * wave-specific: its auto-start threshold and dates (SurveyWaveEndpoint), and
 * copying it (ParentSurveyWaveEndpoint, which needs the parent survey).
 */
class SurveyWaveService implements ParentSurveyScopedInterface, SurveyWaveScopedInterface
{
    use ScopedToParentSurvey;
    use ScopedToSurveyWave;

    public function __construct(
        protected SurveyWaveEndpointInterface $surveyWaveEndpoint,
        protected ParentSurveyWaveEndpointInterface $parentSurveyWaveEndpoint,
    ) {}

    /**
     * Create a new Online wave under the same parent survey, copied from this
     * one. Needs the parent survey scope.
     */
    public function copy(string $surveyName): SurveyModel
    {
        $payload = (new ParentSurveyWaveCopyRequestModel($surveyName))->toArray();

        return SurveyModel::from(
            $this->parentSurveyWaveEndpoint->copy($this->getParentSurveyId(), $this->getWaveId(), $payload)
        );
    }

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
