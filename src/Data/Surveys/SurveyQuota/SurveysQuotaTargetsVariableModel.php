<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
final class SurveysQuotaTargetsVariableModel extends Data
{
    public function __construct(
        public string  $id,
        public ?string $name,
        public bool    $isMulti,
        public int     $displayIndex,
//        /** @var SurveysQuotaTargetsLevelModel[] */
        public ?array  $levels
    ) {}

}
