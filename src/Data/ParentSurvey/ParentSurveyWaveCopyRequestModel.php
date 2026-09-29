<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\ParentSurvey;

use Spatie\LaravelData\Data;

/**
 * Request body for POST /v2/parentSurveys/{parentSurveyId}/waves/{waveId}:
 * the name of the new wave copied from an existing one.
 *
 * Mirrors NfieldPublicApi.Models.ParentSurvey.ParentSurveyWaveCopyRequestModel.
 */
final class ParentSurveyWaveCopyRequestModel extends Data
{
    public function __construct(
        public string $surveyName,
    ) {}
}
