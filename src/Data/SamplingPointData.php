<?php

namespace Nikoleesg\NfieldAdmin\Data;

use Nikoleesg\NfieldAdmin\Enums\SamplingPointKindEnum;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class SamplingPointData extends Data
{
    public function __construct(
        public ?string $sampling_point_id,
        public string $name,
        public ?string $description,
        public ?string $instruction,
        public ?string $fieldwork_office_id,
        public ?string $group_id,
        public ?string $stratum,
        #[WithCast(EnumCast::class)]
        public ?SamplingPointKindEnum $kind
    ) {
    }
}
