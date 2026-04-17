<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
final class SurveyOwnerModel extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $userName,
    ) {}
}
