<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
final class SurveysQuotaTargetsEtagResponseModel extends Data
{
    public function __construct(
        public ?string $id,
        public ?int    $target,
        /** @var Collection<int, SurveysQuotaTargetsEtagVariableModel> */
        public Collection  $variables,
        public int     $successful
    ) {}

}
