<?php

namespace Nikoleesg\NfieldAdmin\Data\Quota;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Concerns\WithDeprecatedCollectionMethod;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
#[MapOutputName(StudlyCaseMapper::class)]
class QuotaAttribute extends Data
{
    use WithDeprecatedCollectionMethod;

    public function __construct(
        public ?string $name,
        public ?string $odinVariable,
        public ?DataCollection $levels
    ) {}


}
