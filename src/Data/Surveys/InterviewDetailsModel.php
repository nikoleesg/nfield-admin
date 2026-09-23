<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Nikoleesg\NfieldAdmin\Enums\InterviewQualityEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

class InterviewDetailsModel extends Data
{
    public function __construct(
        public ?string $id,
        #[WithCast(EnumCast::class)]
        public ?InterviewQualityEnum $interviewQuality,
        public ?string $interviewerId,
        public ?string $samplingPointId,
        public ?string $officeId,
    ) {}
}
