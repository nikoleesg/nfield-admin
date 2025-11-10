<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
final class SurveysQuotaTargetsEtagLevelModel extends Data
{
    public function __construct(
        public string  $id,
        public ?string $name,
        /** @var SurveysQuotaTargetsVariableModel[] */
        public ?array  $variables,
        public ?int    $target,
        public ?int    $maxTarget,
        public ?int    $maxOvershoot,
        public int     $displayIndex,
        public int     $successful
    ) {}

}
