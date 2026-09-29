<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Data;

/**
 * Request body for POST /v2/surveys/{surveyId}/script.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.SurveySetScriptModel.
 */
final class SurveySetScriptModel extends Data
{
    public function __construct(
        public string $script,
        public ?string $fileName = null,
        public bool $unfixedIsOk = false,
    ) {}
}
