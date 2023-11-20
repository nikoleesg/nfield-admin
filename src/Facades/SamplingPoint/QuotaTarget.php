<?php

namespace Nikoleesg\NfieldAdmin\Facades\SamplingPoint;

use Illuminate\Support\Facades\Facade;
use Nikoleesg\NfieldAdmin\Services\SamplingPointsQuotaTargetsService;

class QuotaTarget extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SamplingPointsQuotaTargetsService::class;
    }
}
