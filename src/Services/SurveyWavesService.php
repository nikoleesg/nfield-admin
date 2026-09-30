<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

/**
 * Waves addressed by their own id, reached through `NfieldManager::surveyWaves()`.
 *
 * The API has no list of waves at this level; list them through
 * `NfieldManager::parentSurveys()->forParentSurvey($id)->waves()`.
 */
class SurveyWavesService
{
    /**
     * The settings of one wave.
     */
    public function forWave(string $waveId): SurveyWaveService
    {
        return app(SurveyWaveService::class)->setWaveId($waveId);
    }
}
