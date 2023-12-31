<?php

namespace Nikoleesg\NfieldAdmin\Data;

use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Data\Casts\CarbonCast;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class InterviewerDTO extends Data
{
    public function __construct(
        public string $interviewer_id,
        public string $user_name,
        public ?string $first_name,
        public ?string $last_name,
        public ?string $email_address,
        public ?string $telephone_number,
        #[WithCast(CarbonCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $last_password_change_time,
        public ?string $client_interviewer_id,
        public ?int $successful_count,
        public ?int $unsuccessful_count,
        public ?int $dropped_out_count,
        public ?int $rejected_count,
        #[WithCast(CarbonCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $last_sync_date,
        public ?bool $is_full_synced,
        public ?bool $is_last_sync_successful,
        public bool $is_supervisor,
    ) {
    }
}
