<?php

namespace Nikoleesg\NfieldAdmin\Data;

use Nikoleesg\NfieldAdmin\Enums\SurveyPublishStateEnum;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class SurveyPublishStateData extends Data
{
    public function __construct(
        #[WithCast(EnumCast::class)]
        public SurveyPublishStateEnum $live,
        #[WithCast(EnumCast::class)]
        public SurveyPublishStateEnum $test
    ) {
    }
}
