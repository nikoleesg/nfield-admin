<?php

namespace Nikoleesg\NfieldAdmin\Facades;

use Illuminate\Support\Facades\Facade;
use Nikoleesg\NfieldAdmin\Services\SurveysService;

class Survey extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SurveysService::class;
    }
}
