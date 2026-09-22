<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\Addresses;

use Spatie\LaravelData\Data;

class AddressSampleDataModel extends Data
{
    public function __construct(
        public string $name,
        public string $value
    ) {}
}
