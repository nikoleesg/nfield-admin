<?php

namespace Nikoleesg\NfieldAdmin\Facades;

use Illuminate\Support\Facades\Facade;
use Nikoleesg\NfieldAdmin\Services\NfieldSurveyService;

class Survey extends Facade
{
    protected static function getFacadeAccessor()
    {
        return NfieldSurveyService::class;
    }
}
