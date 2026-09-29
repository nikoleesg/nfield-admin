<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Monitoring;

use Spatie\LaravelData\Data;

/**
 * How often interviews reached a metric's warn and block thresholds.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.Monitoring.MetricCounts.
 */
final class MetricCountsModel extends Data
{
    public function __construct(
        public int $warn = 0,
        public int $block = 0,
    ) {}
}
