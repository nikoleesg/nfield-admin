<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Quota;

use Spatie\LaravelData\Concerns\WithDeprecatedCollectionMethod;
use Spatie\LaravelData\Data;

class QuotaLevel extends Data
{
    use WithDeprecatedCollectionMethod;

    public function __construct(
        public ?string $id,
        public ?string $name,
        public ?int $target,
        public ?int $grossTarget,
        public ?int $maxTarget,
        public ?int $maxOvershoot,
        public ?int $successfulCount,
        public ?int $unsuccessfulCount,
        public ?int $droppedOutCount,
        public ?int $rejectedCount,
        public ?array $attributes
    ) {}

}
