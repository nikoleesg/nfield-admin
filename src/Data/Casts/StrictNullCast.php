<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Uncastable;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class StrictNullCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): string|Uncastable|null
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return trim($value) === '' ? null : $value;
        }

        return Uncastable::create();
    }
}
