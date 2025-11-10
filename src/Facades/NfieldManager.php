<?php

namespace Nikoleesg\NfieldAdmin\Facades;

use Illuminate\Support\Facades\Facade;
use Nikoleesg\NfieldAdmin\Services\SurveysService;

class NfieldManager extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'nfield-manager';
    }

}
