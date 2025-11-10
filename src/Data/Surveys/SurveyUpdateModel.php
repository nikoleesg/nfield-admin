<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Nikoleesg\NfieldAdmin\Data\Casts\StrictNullCast;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
#[MapName(StudlyCaseMapper::class)]
final class SurveyUpdateModel extends Data
{
    public function __construct(
        #[WithCast(StrictNullCast::class)]
        public ?string $survey_name,
        #[WithCast(StrictNullCast::class)]
        public ?string $client_name,
        #[WithCast(StrictNullCast::class)]
        public ?string $description,
        #[WithCast(StrictNullCast::class)]
        public ?string $interviewer_instruction,
    ) {}

}
