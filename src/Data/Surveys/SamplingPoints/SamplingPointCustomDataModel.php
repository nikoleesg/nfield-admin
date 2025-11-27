<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints;

use Spatie\LaravelData\Data;

class SamplingPointCustomDataModel extends Data
{
    public function __construct(
        public ?string $name,
        public ?string $value
    ) {}
}
