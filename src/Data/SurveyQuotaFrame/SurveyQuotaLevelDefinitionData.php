<?php

namespace Nikoleesg\NfieldAdmin\Data\SurveyQuotaFrame;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class SurveyQuotaLevelDefinitionData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }
}
