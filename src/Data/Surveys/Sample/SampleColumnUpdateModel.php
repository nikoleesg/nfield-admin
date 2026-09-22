<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Sample;

use Spatie\LaravelData\Data;

final class SampleColumnUpdateModel extends Data
{
    public function __construct(
        public ?string $columnName = null,
        public mixed $value = null,
    ) {}
}
