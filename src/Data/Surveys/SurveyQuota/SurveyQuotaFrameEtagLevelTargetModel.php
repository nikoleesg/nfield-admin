<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Data;

class SurveyQuotaFrameEtagLevelTargetModel extends Data
{
    public function __construct(
        public ?string $id,
        public ?int $target,
        public ?int $maxTarget,
        public ?int $maxOvershoot
    ) {}

}
