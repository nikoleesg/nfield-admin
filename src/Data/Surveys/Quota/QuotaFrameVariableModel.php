<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Quota;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

final class QuotaFrameVariableModel extends Data
{
    /**
     * @param  array<int, QuotaFrameLevelModel>|null  $levels
     */
    public function __construct(
        public string $id,
        public ?string $name = null,
        public bool $isMulti = false,
        public bool $isForAllocationOnly = false,
        #[DataCollectionOf(QuotaFrameLevelModel::class)]
        public ?array $levels = null,
        public int $displayIndex = 0,
        public bool $isHidden = false,
    ) {}
}
