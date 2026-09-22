<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

final class SurveysQuotaTargetsResponseModel extends Data
{
    /**
     * @param  array<int, SurveysQuotaTargetsVariableModel>|null  $variables
     */
    public function __construct(
        public ?string $id = null,
        public ?int $target = null,
        public ?int $rootLevelMaxOvershoot = null,
        #[DataCollectionOf(SurveysQuotaTargetsVariableModel::class)]
        public ?array $variables = null,
    ) {}
}
