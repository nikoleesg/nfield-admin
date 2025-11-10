<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapName(StudlyCaseMapper::class)]
final class SurveysQuotaFrameLevelModel extends Data
{
    public function __construct(
        public string $id,
        public string $definitionId,
        public ?int   $target,
        public ?int   $max_target,
        public ?int   $max_overshoot,
        /** @var SurveysQuotaFrameVariableModel[] */
        public ?array $variables,
        public bool   $isHidden
    ) {}

}
