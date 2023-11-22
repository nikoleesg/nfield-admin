<?php

namespace Nikoleesg\NfieldAdmin\Facades;

use Illuminate\Support\Facades\Facade;
use Nikoleesg\NfieldAdmin\Services\SamplingPointService;

class SamplingPoint extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SamplingPointService::class;
    }
}
