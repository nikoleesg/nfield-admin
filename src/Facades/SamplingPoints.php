<?php

namespace Nikoleesg\NfieldAdmin\Facades;

use Illuminate\Support\Facades\Facade;
use Nikoleesg\NfieldAdmin\Services\SamplingPointsService;

class SamplingPoints extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SamplingPointsService::class;
    }
}
