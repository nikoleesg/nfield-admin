<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints;

use Spatie\LaravelData\Data;

final class ActivateSpareSamplingPointsResponseModel extends Data
{
    public function __construct(
        public bool $isActivated = false,
    ) {}
}
