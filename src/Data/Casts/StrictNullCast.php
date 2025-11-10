<?php

namespace Nikoleesg\NfieldAdmin\Data\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Uncastable;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class StrictNullCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): string|Uncastable|null
    {
        return empty(trim($value)) ? null : $value;
    }
}
