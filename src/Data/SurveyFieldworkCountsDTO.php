<?php

namespace Nikoleesg\NfieldAdmin\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class SurveyFieldworkCountsDTO extends Data
{
    public function __construct(
        public string $survey_id,
        public int $successful,
        public int $successful_last_24_hours,
        public int $screened_out,
        public int $dropped_out,
        public int $rejected,
        public int $successful_deleted,
        public int $screened_out_deleted,
        public int $dropped_out_deleted,
        public int $rejected_deleted,
        public int $active_interviews,
        #[DataCollectionOf(ScreenedOutOverviewDTO::class)]
        public ?DataCollection $screened_out_overview
    ) {
    }

}
