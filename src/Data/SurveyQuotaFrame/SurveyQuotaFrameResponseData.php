<?php


namespace Nikoleesg\NfieldAdmin\Data\SurveyQuotaFrame;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class SurveyQuotaFrameResponseData extends Data
{
    public function __construct(
        public string $id,
        public ?int $quota_e_tag,
        public int $target,
        #[DataCollectionOf(SurveyQuotaVariableDefinitionData::class)]
        public DataCollection $variable_definitions,
        #[DataCollectionOf(SurveyQuotaFrameVariableData::class)]
        public DataCollection $frame_variables,
    ) {
    }
}
