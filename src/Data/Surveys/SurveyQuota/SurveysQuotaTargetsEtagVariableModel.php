<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

final class SurveysQuotaTargetsEtagVariableModel extends Data
{
    /**
     * @param  array<int, SurveysQuotaTargetsEtagLevelModel>|null  $levels
     */
    public function __construct(
        public string $id,
        public ?string $name = null,
        public bool $isMulti = false,
        public int $displayIndex = 0,
        #[DataCollectionOf(SurveysQuotaTargetsEtagLevelModel::class)]
        public ?array $levels = null,
    ) {}
}
