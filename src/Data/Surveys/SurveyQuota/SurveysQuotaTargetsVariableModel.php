<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

final class SurveysQuotaTargetsVariableModel extends Data
{
    /**
     * @param  array<int, SurveysQuotaTargetsLevelModel>|null  $levels
     */
    public function __construct(
        public string $id,
        public ?string $name = null,
        public bool $isMulti = false,
        public int $displayIndex = 0,
        #[DataCollectionOf(SurveysQuotaTargetsLevelModel::class)]
        public ?array $levels = null,
    ) {}
}
