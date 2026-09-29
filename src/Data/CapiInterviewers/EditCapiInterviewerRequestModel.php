<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\CapiInterviewers;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class EditCapiInterviewerRequestModel extends Data
{
    public function __construct(
        public string|Optional|null $firstName = new Optional,
        public string|Optional|null $lastName = new Optional,
        public string|Optional|null $emailAddress = new Optional,
        public string|Optional|null $telephoneNumber = new Optional,
        public bool|Optional $isSupervisor = new Optional,
    ) {}
}
