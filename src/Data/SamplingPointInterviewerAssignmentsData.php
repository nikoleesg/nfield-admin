<?php

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class SamplingPointInterviewerAssignmentsData extends Data
{
    public function __construct(
        public array $sampling_point_ids,
        public array $interviewer_ids
    ) {
    }
}
