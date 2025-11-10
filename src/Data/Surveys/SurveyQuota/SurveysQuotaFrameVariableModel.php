<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
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
