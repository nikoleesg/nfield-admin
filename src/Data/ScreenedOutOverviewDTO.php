<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Data;

class ScreenedOutOverviewDTO extends Data
{
    public function __construct(
        public string $responseCode,
        public string $count
    ) {}
}
