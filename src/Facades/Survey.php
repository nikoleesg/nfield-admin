<?php

namespace Nikoleesg\NfieldAdmin\Facades;

use Illuminate\Support\Facades\Facade;
use Nikoleesg\NfieldAdmin\Services\NfieldSurveyService;
use Nikoleesg\NfieldAdmin\Services\SurveysService;

class Survey extends Facade
{
    protected static function getFacadeAccessor()
    {
//        return NfieldSurveyService::class;
        return SurveysService::class;
    }
}
