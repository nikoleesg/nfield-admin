<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Sample;

use Spatie\LaravelData\Data;

final class SampleUpdateStatus extends Data
{
    public function __construct(
        public bool $resultStatus = false,
    ) {}
}
