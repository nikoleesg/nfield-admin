<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class SurveyUpdateModel extends Data
{
    public function __construct(
        public string|Optional|null $surveyName = new Optional,
        public string|Optional|null $clientName = new Optional,
        public string|Optional|null $description = new Optional,
        public string|Optional|null $interviewerInstruction = new Optional,
    ) {}
}
