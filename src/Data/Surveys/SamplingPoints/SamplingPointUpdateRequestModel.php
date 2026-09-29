<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints;

use Nikoleesg\NfieldAdmin\Enums\SamplingPointKindEnum;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class SamplingPointUpdateRequestModel extends Data
{
    /**
     * @param  array<int, SamplingPointCustomDataModel>|Optional|null  $customDataItems
     */
    public function __construct(
        public string $name,
        public string|Optional|null $description = new Optional,
        public string|Optional|null $fieldworkOfficeId = new Optional,
        public string|Optional|null $groupId = new Optional,
        public string|Optional|null $stratum = new Optional,
        #[DataCollectionOf(SamplingPointCustomDataModel::class)]
        public array|Optional|null $customDataItems = new Optional,
        #[WithCast(EnumCast::class)]
        public SamplingPointKindEnum|Optional|null $kind = new Optional,
    ) {}
}
