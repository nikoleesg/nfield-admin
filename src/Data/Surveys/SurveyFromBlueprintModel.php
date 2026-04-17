<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Data;

final class SurveyFromBlueprintModel extends Data
{
    public function __construct(
        public string $surveyName,
        public string $blueprintSurveyId,
        public bool $enableRespondentsGateway = false,
    ) {}
}
