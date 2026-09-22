<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

final class SurveysQuotaTargetsEtagResponseModel extends Data
{
    /**
     * @param  array<int, SurveysQuotaTargetsEtagVariableModel>|null  $variables
     */
    public function __construct(
        public ?string $id = null,
        public ?int $target = null,
        public ?int $rootLevelMaxOvershoot = null,
        #[DataCollectionOf(SurveysQuotaTargetsEtagVariableModel::class)]
        public ?array $variables = null,
        public int $successful = 0,
    ) {}
}
