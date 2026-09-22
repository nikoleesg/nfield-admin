<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Data;

final class SurveyBaseModel extends Data
{
    public function __construct(
        public string $surveyId,
        public string $surveyName,
    ) {}
}
