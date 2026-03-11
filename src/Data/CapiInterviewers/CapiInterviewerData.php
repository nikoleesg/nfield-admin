<?php

namespace Nikoleesg\NfieldAdmin\Data\CapiInterviewers;

use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Data\Casts\CarbonCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

final class CapiInterviewerData extends Data
{
    public function __construct(
        public ?string $interviewerId = null,
        public ?string $userName = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $emailAddress = null,
        public ?string $telephoneNumber = null,
        #[WithCast(CarbonCast::class)]
        public ?Carbon $lastPasswordChangeTime = null,
        public ?string $clientInterviewerId = null,
        public int $successfulCount = 0,
        public int $unsuccessfulCount = 0,
        public int $droppedOutCount = 0,
        public int $rejectedCount = 0,
        #[WithCast(CarbonCast::class)]
        public ?Carbon $lastSyncDate = null,
        public bool $isFullSynced = false,
        public bool $isLastSyncSuccessful = false,
        public bool $isSupervisor = false,
    ) {}
}
