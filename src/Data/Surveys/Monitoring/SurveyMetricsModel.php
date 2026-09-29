<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Monitoring;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

/**
 * A survey's performance metrics, for live or for test interviews.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.Monitoring.SurveyMetrics.
 */
final class SurveyMetricsModel extends Data
{
    /**
     * @param  list<SurveyMetricCountsModel>|null  $counts
     */
    public function __construct(
        public ?string $id = null,
        public int $publishedCount = 0,
        public int $totalCount = 0,
        #[DataCollectionOf(SurveyMetricCountsModel::class)]
        public ?array $counts = null,
    ) {}
}
