<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class SurveyUpdateSampleRecordDTO extends Data
{
    public function __construct(
        public int $sampleRecordId,
        #[DataCollectionOf(SampleColumnUpdateDTO::class)]
        public ?DataCollection $columnUpdates
    ) {}

    public static function fromResponse(array $sample): self
    {
        return new self(
            $sample['sampleRecordId'],
            ! empty($sample['columnUpdates']) ? SampleColumnUpdateDTO::collect($sample['columnUpdates']) : null
        );
    }
}
