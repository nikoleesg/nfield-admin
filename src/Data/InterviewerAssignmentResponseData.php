<?php

namespace Nikoleesg\NfieldAdmin\Data;

use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Data\Casts\CarbonCast;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class InterviewerAssignmentResponseData extends Data
{
    public function __construct(
        public string $survey_name,
        public string $survey_id,
        public string $interviewer,
        public string $interviewer_id,
        public ?string $discriminator,
        public ?bool $assigned,
        public ?bool $active,
        public ?bool $is_group_assignment,
        public ?int $assigned_target,
        public ?int $assigned_sampling_point_target,
        public ?int $successful,
        public ?int $screened_out,
        public ?int $dropped_out,
        public ?int $rejected,
        #[WithCast(CarbonCast::class)]
        public ?Carbon $last_sync_date,
        public ?bool $is_full_synced,
        public ?bool $is_last_sync_successful
    ) {
    }
}
