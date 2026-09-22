<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints;

use Spatie\LaravelData\Data;

final class SamplingPointQuotaLevelTargetModel extends Data
{
    public function __construct(
        public ?string $surveyId = null,
        public ?string $samplingPointId = null,
        public ?SamplingPointResponseModel $samplingPoint = null,
        public ?string $levelId = null,
        public ?int $target = null,
        public ?int $maxTarget = null,
    ) {}
}
