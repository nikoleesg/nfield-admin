<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\ParentSurvey;

use Spatie\LaravelData\Data;

/**
 * Request body for POST /v2/parentSurveys/{parentSurveyId}/waves (an Online wave).
 *
 * Mirrors NfieldPublicApi.Models.ParentSurvey.ParentSurveyWaveCreateRequestModel.
 */
final class ParentSurveyWaveCreateRequestModel extends Data
{
    public function __construct(
        public string $surveyName,
        public ?string $clientName = null,
        public ?string $description = null,
    ) {}
}
