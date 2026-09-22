<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Data;

final class SurveysQuotaFrameVariableModel extends Data
{
    public function __construct(
        public string $id,
        public string $definitionId,
        /** @var SurveysQuotaFrameLevelModel[] */
        public ?array $levels,
        public bool $isHidden
    ) {}
}
