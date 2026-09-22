<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Data;

/**
 * @deprecated
 */
class AddressSampleDTO extends Data
{
    public function __construct(
        public string $name,
        public string $value
    ) {}
}
