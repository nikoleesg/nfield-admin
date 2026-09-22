<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Data;

final class SurveyDataInterviewRequestModel extends Data
{
    public function __construct(
        public ?string $fileName = null,
    ) {}
}
