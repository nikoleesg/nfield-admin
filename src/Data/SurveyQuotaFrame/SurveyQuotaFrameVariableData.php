<?php


namespace Nikoleesg\NfieldAdmin\Data\SurveyQuotaFrame;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class SurveyQuotaFrameVariableData extends Data
{
    public function __construct(
        public string $id,
        public string $definition_id,
        #[DataCollectionOf(SurveyQuotaFrameLevelData::class)]
        public DataCollection $levels,
        public bool $is_hidden
    ) {
    }

}
