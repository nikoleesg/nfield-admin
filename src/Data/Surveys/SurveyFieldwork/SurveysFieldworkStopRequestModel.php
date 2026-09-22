<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyFieldwork;

use Nikoleesg\NfieldAdmin\Enums\InterviewingRestrictionTypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

final class SurveysFieldworkStopRequestModel extends Data
{
    public function __construct(
        #[WithCast(EnumCast::class)]
        public InterviewingRestrictionTypeEnum $interviewingRestrictionType = InterviewingRestrictionTypeEnum::BlockEverything,
    ) {}
}
