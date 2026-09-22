<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Data;

final class ResponseCodeCount extends Data
{
    public function __construct(
        public int $responseCode,
        public int $count,
    ) {}

}
