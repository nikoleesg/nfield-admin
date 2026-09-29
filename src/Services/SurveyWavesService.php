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
     * One wave, without its parent survey. Its settings are available;
     * copy() needs the parent, so reach the wave through its parent survey
     * to copy it.
     */
    public function forWave(string $waveId): SurveyWaveService
    {
        return app(SurveyWaveService::class)->setWaveId($waveId);
    }
}
