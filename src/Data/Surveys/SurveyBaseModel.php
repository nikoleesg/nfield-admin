<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class SurveyBaseModel extends Data
{
    public function __construct(
        public string $SurveyId,
        public string $SurveyName,
    ) {}

}
