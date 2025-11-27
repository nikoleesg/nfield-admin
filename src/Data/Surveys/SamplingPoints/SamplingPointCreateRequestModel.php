<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints;

use Nikoleesg\NfieldAdmin\Enums\SamplingPointKindEnum;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(StudlyCaseMapper::class)]
class SamplingPointCreateRequestModel extends Data
{
    public function __construct(
        public string $name,
        public ?string $description,
        public ?string $fieldworkOfficeId,
        public ?string $groupId,
        public ?string $stratum,
        public ?array $customDataItems,
        #[WithCast(EnumCast::class)]
        public ?SamplingPointKindEnum $kind,
        public ?string $samplingPointId,
    ) {
    }
}
