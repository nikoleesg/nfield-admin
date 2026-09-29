<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Monitoring;

use Spatie\LaravelData\Data;

/**
 * The warn and block counts of one performance metric.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.Monitoring.SurveyMetricCounts. The
 * spec documents the metric names Page Complexity, Expression Complexity,
 * Interview State Size and Expensive Command Count; it stays a string so a new
 * metric does not break parsing.
 */
final class SurveyMetricCountsModel extends Data
{
    public function __construct(
        public ?string $metricName = null,
        public ?MetricCountsModel $all = null,
        public ?MetricCountsModel $published = null,
    ) {}
}
