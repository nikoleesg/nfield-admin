<?php

namespace Nikoleesg\NfieldAdmin\Facades;

use Illuminate\Support\Facades\Facade;
use Nikoleesg\NfieldAdmin\Services\BackgroundService;

class Background extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BackgroundService::class;
    }
}
