<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Data;

final class SurveysQuotaTargetsVariableModel extends Data
{
    public function __construct(
        public string $id,
        public ?string $name,
        public bool $isMulti,
        public int $displayIndex,
        //        /** @var SurveysQuotaTargetsLevelModel[] */
        public ?array $levels
    ) {}

}
