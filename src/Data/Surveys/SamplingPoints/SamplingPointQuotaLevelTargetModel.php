<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class SamplingPointQuotaLevelTargetModel extends Data
{
    public function __construct(
        public string|Optional|null $surveyId = new Optional,
        public string|Optional|null $samplingPointId = new Optional,
        public SamplingPointResponseModel|Optional|null $samplingPoint = new Optional,
        public string|Optional|null $levelId = new Optional,
        public int|Optional|null $target = new Optional,
        public int|Optional|null $maxTarget = new Optional,
    ) {}
}
