<?php

namespace Nikoleesg\NfieldAdmin\Data\SurveyQuotaFrame;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class SurveyQuotaVariableDefinitionData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $odin_variable_name,
        public ?bool $is_selection_optional,
        public bool $is_multi,
        #[DataCollectionOf(SurveyQuotaLevelDefinitionData::class)]
        public DataCollection $levels,
    ) {
    }
}
