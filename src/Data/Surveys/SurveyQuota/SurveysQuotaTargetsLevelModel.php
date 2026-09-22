<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

final class SurveysQuotaTargetsLevelModel extends Data
{
    /**
     * @param  array<int, SurveysQuotaTargetsVariableModel>|null  $variables
     */
    public function __construct(
        public string $id,
        public ?string $name = null,
        #[DataCollectionOf(SurveysQuotaTargetsVariableModel::class)]
        public ?array $variables = null,
        public ?int $target = null,
        public ?int $maxTarget = null,
        public ?int $maxOvershoot = null,
        public int $displayIndex = 0,
    ) {}
}
