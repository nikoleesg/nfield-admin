<?php

namespace Nikoleesg\NfieldAdmin\Data\CapiInterviewers;

use Spatie\LaravelData\Data;

final class CapiInterviewerResponseData extends Data
{
    public function __construct(
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $emailAddress = null,
        public ?string $telephoneNumber = null,
        public bool $isSupervisor = false,
        public ?string $interviewerId = null,
        public ?string $userName = null,
        public ?string $clientInterviewerId = null,
    ) {}
}
