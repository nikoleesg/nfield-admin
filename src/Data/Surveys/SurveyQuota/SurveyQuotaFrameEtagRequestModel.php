<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Data;

class SurveyQuotaFrameEtagRequestModel extends Data
{
    public function __construct(
        /** @var SurveyQuotaFrameEtagLevelTargetModel[] */
        public array $levels
    ) {}
}
