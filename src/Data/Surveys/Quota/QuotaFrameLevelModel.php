<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Quota;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

final class QuotaFrameLevelModel extends Data
{
    /**
     * @param  array<int, QuotaFrameVariableModel>|null  $variables
     */
    public function __construct(
        public string $id,
        public ?string $name = null,
        #[DataCollectionOf(QuotaFrameVariableModel::class)]
        public ?array $variables = null,
        public ?int $target = null,
        public ?int $maxTarget = null,
        public ?int $maxOvershoot = null,
        public int $successful = 0,
        public int $quotaFailCount = 0,
        public int $displayIndex = 0,
        public bool $isHidden = false,
    ) {}
}
