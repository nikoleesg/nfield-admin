<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Sample;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

final class SurveyUpdateSampleRecordModel extends Data
{
    /**
     * @param  array<int, SampleColumnUpdateModel>|null  $columnUpdates
     */
    public function __construct(
        public int $sampleRecordId,
        #[DataCollectionOf(SampleColumnUpdateModel::class)]
        public ?array $columnUpdates = null,
    ) {}
}
