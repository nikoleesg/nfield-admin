<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
final class SurveysQuotaVariableDefinitionModel extends Data
{
    public function __construct(
        public string  $id,
        public ?string $name,
        public ?string $odinVariableName,
        public ?bool    $isSelectionOptional,
        public bool    $isMulti,
        /** @var SurveysQuotaLevelDefinitionModel[] */
        public ?array  $levels
    ) {}
}
