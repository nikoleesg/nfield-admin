<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints;

use Spatie\LaravelData\Data;

final class SamplingPointQuotaTargetModel extends Data
{
    public function __construct(
        public ?string $levelId = null,
        public ?int $target = null,
        public int $successfulCount = 0,
        public int $unsuccessfulCount = 0,
        public int $droppedOutCount = 0,
        public int $rejectedCount = 0,
    ) {}
}
