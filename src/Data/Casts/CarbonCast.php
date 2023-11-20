<?php

namespace Nikoleesg\NfieldAdmin\Data\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Uncastable;
use Spatie\LaravelData\Support\DataProperty;
use Carbon\Carbon;

class CarbonCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $context): Carbon|Uncastable
    {
        return Carbon::parse($value);
    }

}
