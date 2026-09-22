<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Concerns\WithDeprecatedCollectionMethod;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class SampleColumnUpdateDTO extends Data
{
    use WithDeprecatedCollectionMethod;

    public function __construct(
        public string $column_name,
        public string $value
    ) {}
}
