<?php

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class AddressSampleDTO extends Data
{
    public function __construct(
        public string $name,
        public string $value
    ) {
    }
}
