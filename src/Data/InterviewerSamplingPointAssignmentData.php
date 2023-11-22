<?php

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class InterviewerSamplingPointAssignmentData extends Data
{
    public function __construct(
        public string $interviewer_id,
        public string $user_name,
        public ?string $first_name,
        public ?string $last_name,
        public ?bool $assigned,
        public ?bool $active,
    ) {
    }
}
