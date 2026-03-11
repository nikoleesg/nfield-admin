<?php

namespace Nikoleesg\NfieldAdmin\Data\CapiInterviewers;

use Spatie\LaravelData\Data;

final class EditCapiInterviewerRequestData extends Data
{
    public function __construct(
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $emailAddress = null,
        public ?string $telephoneNumber = null,
        public ?bool $isSupervisor = null,
    ) {}
}
