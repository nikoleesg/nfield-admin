<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\SurveyQuotaFrame;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class SurveyQuotaVariableDefinitionData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $odinVariableName,
        public ?bool $isSelectionOptional,
        public bool $isMulti,
        #[DataCollectionOf(SurveyQuotaLevelDefinitionData::class)]
        public DataCollection $levels,
    ) {}
}
