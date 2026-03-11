<?php

namespace Nikoleesg\NfieldAdmin\Data\CapiInterviewers;

use Spatie\LaravelData\Data;

final class ResetCapiInterviewerPasswordRequestData extends Data
{
    public function __construct(
        public string $password,
    ) {}
}
