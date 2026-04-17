<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Nikoleesg\NfieldAdmin\Enums\SurveyPublishStateEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

class SurveyPublishStateModel extends Data
{
    public function __construct(
        #[WithCast(EnumCast::class)]
        public SurveyPublishStateEnum $live,
        #[WithCast(EnumCast::class)]
        public SurveyPublishStateEnum $test,
    ) {}
}
