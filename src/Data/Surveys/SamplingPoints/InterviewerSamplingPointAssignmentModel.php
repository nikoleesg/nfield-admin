<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints;

use Spatie\LaravelData\Data;

final class InterviewerSamplingPointAssignmentModel extends Data
{
    public function __construct(
        public ?string $interviewerId = null,
        public ?string $userName = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public bool $assigned = false,
        public bool $active = false,
    ) {}
}
