<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(StudlyCaseMapper::class)]
final class SurveysQuotaFrameRequestModel extends Data
{
    public function __construct(
        public ?int $target,
        /** @var SurveysQuotaVariableDefinitionModel[] */
        public ?array $variableDefinitions,
        /** @var SurveysQuotaFrameVariableModel[] */
        public ?array $frameVariables
    ) {}

}
