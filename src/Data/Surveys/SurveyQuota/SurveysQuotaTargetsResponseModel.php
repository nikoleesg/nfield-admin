<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
final class SurveysQuotaTargetsResponseModel extends Data
{
    public function __construct(
        public ?string $id,
        public ?int    $target,
        /** @var SurveysQuotaTargetsVariableModel[] */
        public ?array  $variables,
    ) {}

}
