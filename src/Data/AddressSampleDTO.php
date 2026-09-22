<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

/**
 * @deprecated
 */
#[MapInputName(StudlyCaseMapper::class)]
class AddressSampleDTO extends Data
{
    public function __construct(
        public string $name,
        public string $value
    ) {}
}
