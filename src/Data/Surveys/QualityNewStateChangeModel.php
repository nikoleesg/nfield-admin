<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Nikoleesg\NfieldAdmin\Enums\InterviewQualityEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

class QualityNewStateChangeModel extends Data
{
    public function __construct(
        public string $interviewId,
        #[WithCast(EnumCast::class)]
        public InterviewQualityEnum $newState,
    ) {}
}
