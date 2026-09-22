<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Sample;

use Spatie\LaravelData\Data;

/**
 * A single sample filter clause.
 *
 * Supported operators: strt, con, ncon, eq, lt, gt, lte, gte, neq, in.
 */
final class SampleFilterModel extends Data
{
    public function __construct(
        public string $name,
        public string $op,
        public string $value,
    ) {}
}
