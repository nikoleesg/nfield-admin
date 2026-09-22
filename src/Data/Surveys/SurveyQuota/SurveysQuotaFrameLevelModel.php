<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Data;

final class SurveysQuotaFrameLevelModel extends Data
{
    public function __construct(
        public string $id,
        public string $definitionId,
        public ?int $target,
        public ?int $maxTarget,
        public ?int $maxOvershoot,
        /** @var SurveysQuotaFrameVariableModel[] */
        public ?array $variables,
        public bool $isHidden
    ) {}

}
