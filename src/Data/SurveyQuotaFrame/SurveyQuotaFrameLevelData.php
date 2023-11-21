<?php

namespace Nikoleesg\NfieldAdmin\Data\SurveyQuotaFrame;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class SurveyQuotaFrameLevelData extends Data
{
    public function __construct(
        public string $id,
        public string $definition_id,
        public ?int $target,
        public ?int $max_target,
        public ?int $max_overshoot,
        #[DataCollectionOf(SurveyQuotaFrameVariableData::class)]
        public DataCollection $variables,
        public bool $is_hidden
    ) {
    }

    public static function fromResponse(array $data): self
    {
        return new self(
            $data['Id'],
            $data['DefinitionId'],
            $data['Target'],
            $data['MaxTarget'],
            $data['MaxOvershoot'],
            ! empty($data['Variables']) ? SurveyQuotaFrameVariableData::collection($data['Variables']) : SurveyQuotaFrameVariableData::collection([]),
            $data['IsHidden']
        );
    }
}
