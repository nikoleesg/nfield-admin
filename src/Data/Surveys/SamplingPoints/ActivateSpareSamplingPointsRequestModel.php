<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints;

use Spatie\LaravelData\Data;

final class ActivateSpareSamplingPointsRequestModel extends Data
{
    /**
     * @param  array<int, string>|null  $samplingPointIds
     */
    public function __construct(
        public ?array $samplingPointIds = null,
    ) {}
}
