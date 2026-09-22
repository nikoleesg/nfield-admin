<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints;

use Nikoleesg\NfieldAdmin\Enums\SamplingPointKindEnum;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

final class SamplingPointUpdateRequestModel extends Data
{
    /**
     * @param  array<int, SamplingPointCustomDataModel>|null  $customDataItems
     */
    public function __construct(
        public string $name,
        public ?string $description = null,
        public ?string $fieldworkOfficeId = null,
        public ?string $groupId = null,
        public ?string $stratum = null,
        #[DataCollectionOf(SamplingPointCustomDataModel::class)]
        public ?array $customDataItems = null,
        #[WithCast(EnumCast::class)]
        public ?SamplingPointKindEnum $kind = null,
    ) {}
}
