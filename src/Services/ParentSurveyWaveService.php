<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ParentSurveyWaveEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\ParentSurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyWaveScopedInterface;
use Nikoleesg\NfieldAdmin\Data\ParentSurvey\ParentSurveyWaveCopyRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToParentSurvey;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurveyWave;

/**
 * One wave of a parent survey, reached through
 * `$parentSurvey->waves()->forWave($waveId)`.
 *
 * Services mirror the endpoint naming: this pairs with ParentSurveyWaveEndpoint,
 * and {@see ParentSurveyWaveCollectionService} with
 * ParentSurveyWaveCollectionEndpoint. The wave's own settings, which the API
 * addresses by the wave id alone, are {@see SurveyWaveService}.
 */
class ParentSurveyWaveService implements ParentSurveyScopedInterface, SurveyWaveScopedInterface
{
    use ScopedToParentSurvey;
    use ScopedToSurveyWave;

    public function __construct(
        protected ParentSurveyWaveEndpointInterface $parentSurveyWaveEndpoint,
    ) {}

    /**
     * Create a new Online wave under the same parent survey, copied from this one.
     */
    public function copy(string $surveyName): SurveyModel
    {
        $payload = (new ParentSurveyWaveCopyRequestModel($surveyName))->toArray();

        return SurveyModel::from(
            $this->parentSurveyWaveEndpoint->copy($this->getParentSurveyId(), $this->getWaveId(), $payload)
        );
    }
}
