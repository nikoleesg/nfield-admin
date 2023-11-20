<?php

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class SamplingPointQuotaTargetData extends Data
{
    public function __construct(
        public string $level_id,
        public int $target,
        public int $successful_count,
        public int $unsuccessful_count,
        public int $dropped_out_count,
        public int $rejected_count,
    ) {
    }
}
