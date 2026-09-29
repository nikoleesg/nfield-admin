<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Data;

/**
 * The survey group a survey is moved to; the body and the response of
 * PUT /v2/surveys/{surveyId}/surveyGroup.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.SurveyMoveModel.
 */
final class SurveyMoveModel extends Data
{
    public function __construct(
        public int $surveyGroupId,
    ) {}
}
