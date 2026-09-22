<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Sample;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

final class ClearSurveySampleModel extends Data
{
    /**
     * @param  array<int, SampleFilterModel>|null  $filters
     * @param  array<int, string>|null  $columns
     */
    public function __construct(
        #[DataCollectionOf(SampleFilterModel::class)]
        public ?array $filters = null,
        public ?array $columns = null,
    ) {}
}
