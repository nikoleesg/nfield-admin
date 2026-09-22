<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Nikoleesg\NfieldAdmin\Data\Casts\StrictNullCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

final class SurveyUpdateModel extends Data
{
    public function __construct(
        #[WithCast(StrictNullCast::class)]
        public ?string $surveyName,
        #[WithCast(StrictNullCast::class)]
        public ?string $clientName,
        #[WithCast(StrictNullCast::class)]
        public ?string $description,
        #[WithCast(StrictNullCast::class)]
        public ?string $interviewerInstruction,
    ) {}

}
