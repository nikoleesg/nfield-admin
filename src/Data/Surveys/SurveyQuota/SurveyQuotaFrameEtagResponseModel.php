<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Data;

class SurveyQuotaFrameEtagResponseModel extends Data
{
    public function __construct(
        /** @var SurveyQuotaFrameEtagLevelTargetModel[] */
        public array $levels
    ) {}
}
