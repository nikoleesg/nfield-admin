<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Concerns\WithDeprecatedCollectionMethod;
use Spatie\LaravelData\Data;

class SampleColumnUpdateDTO extends Data
{
    use WithDeprecatedCollectionMethod;

    public function __construct(
        public string $columnName,
        public string $value
    ) {}
}
