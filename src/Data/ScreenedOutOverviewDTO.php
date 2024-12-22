<?php

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class ScreenedOutOverviewDTO extends Data
{
    public function __construct(
        public string $response_code,
        public string $count
    ) {
    }
}
