<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints;

use Spatie\LaravelData\Data;

final class ReplaceSamplingPointWithSpareRequestModel extends Data
{
    public function __construct(
        public ?string $spareSamplingPointId = null,
        public ?int $target = null,
    ) {}
}
