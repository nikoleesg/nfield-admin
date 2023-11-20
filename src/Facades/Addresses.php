<?php

namespace Nikoleesg\NfieldAdmin\Facades;

use Illuminate\Support\Facades\Facade;
use Nikoleesg\NfieldAdmin\Services\AddressesService;

class Addresses extends Facade
{
    protected static function getFacadeAccessor()
    {
        return AddressesService::class;
    }
}
