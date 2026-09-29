<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class ReplaceSamplingPointWithSpareRequestModel extends Data
{
    public function __construct(
        public string|Optional|null $spareSamplingPointId = new Optional,
        public int|Optional|null $target = new Optional,
    ) {}
}
