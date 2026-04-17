<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
#[MapOutputName(StudlyCaseMapper::class)]
final class SurveySettingModel extends Data
{
    public function __construct(
        public string $name,
        public string $value,
    ) {}
}
