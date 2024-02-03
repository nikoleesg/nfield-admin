<?php

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class SurveyUpdateSampleRecordDTO extends Data
{
    public function __construct(
        public int $sample_record_id,
        #[DataCollectionOf(SampleColumnUpdateDTO::class)]
        public ?DataCollection $column_updates
    ) {
    }

    public static function fromResponse(array $sample): self
    {
        return new self(
            $sample['SampleRecordId'] ?? $sample['sample_record_id'],
            ! empty($sample['ColumnUpdates']) ? SampleColumnUpdateDTO::collection($sample['ColumnUpdates']) : null
        );
    }
}
