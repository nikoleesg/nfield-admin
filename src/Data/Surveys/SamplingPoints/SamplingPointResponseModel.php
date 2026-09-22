<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints;

use Nikoleesg\NfieldAdmin\Enums\SamplingPointKindEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

class SamplingPointResponseModel extends Data
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
    ) {}
}
