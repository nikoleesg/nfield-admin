<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapOutputName(CamelCaseMapper::class)]
#[MapInputName(StudlyCaseMapper::class)]
final class SurveysQuotaVariableDefinitionModel extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $odinVariableName,
        public ?bool $isSelectionOptional,
        public bool $isMulti,
        public bool $isTargetable,
        /** @var SurveysQuotaLevelDefinitionModel[] */
        public ?array $levels
    ) {}
}
