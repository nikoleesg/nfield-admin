<?php

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;
use Spatie\LaravelData\Concerns\WithDeprecatedCollectionMethod;

#[MapInputName(StudlyCaseMapper::class)]
class SampleColumnUpdateDTO extends Data
{
    use WithDeprecatedCollectionMethod;

    public function __construct(
        public string $column_name,
        public string $value
    ) {
    }
}
