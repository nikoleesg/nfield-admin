<?php

namespace Nikoleesg\NfieldAdmin\Data\CapiInterviewers;

use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Data\Casts\CarbonCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

final class CapiInterviewerAssignmentData extends Data
{
    public function __construct(
        public ?string $surveyName = null,
        public ?string $surveyId = null,
        public ?string $interviewer = null,
        public ?string $interviewerId = null,
        public ?string $discriminator = null,
        public ?bool $assigned = null,
        public ?bool $active = null,
        public ?bool $isGroupAssignment = null,
        public ?int $assignedTarget = null,
        public ?int $assignedSamplingPointTarget = null,
        public int $successful = 0,
        public int $screenedOut = 0,
        public int $droppedOut = 0,
        public int $rejected = 0,
        #[WithCast(CarbonCast::class)]
        public ?Carbon $lastSyncDate = null,
        public ?bool $isFullSynced = null,
        public ?bool $isLastSyncSuccessful = null,
    ) {}
}
