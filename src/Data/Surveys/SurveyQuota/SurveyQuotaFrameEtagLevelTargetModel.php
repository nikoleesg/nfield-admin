<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
#[MapOutputName(CamelCaseMapper::class)]
class SurveyQuotaFrameEtagLevelTargetModel extends Data
{
    public function __construct(
        public ?string $id,
        public ?int $target,
        public ?int $maxTarget,
        public ?int $maxOvershoot
    ) {}

}
