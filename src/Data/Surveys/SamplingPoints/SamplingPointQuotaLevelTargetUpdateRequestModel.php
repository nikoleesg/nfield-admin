<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class SamplingPointQuotaLevelTargetUpdateRequestModel extends Data
{
    public function __construct(
        public int|Optional|null $target = new Optional,
    ) {}
}
