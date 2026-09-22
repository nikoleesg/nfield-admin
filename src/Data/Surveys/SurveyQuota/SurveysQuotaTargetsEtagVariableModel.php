<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Data;

final class SurveysQuotaTargetsEtagVariableModel extends Data
{
    public function __construct(
        public string $id,
        public ?string $name,
        public bool $isMulti,
        public int $displayIndex,
        /** @var SurveysQuotaTargetsEtagLevelModel[] */
        public ?array $levels
    ) {}

}
