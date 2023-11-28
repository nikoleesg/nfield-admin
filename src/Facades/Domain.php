<?php

namespace Nikoleesg\NfieldAdmin\Facades;

use Illuminate\Support\Facades\Facade;
use Nikoleesg\NfieldAdmin\Services\DomainService;

class Domain extends Facade
{
    protected static function getFacadeAccessor()
    {
        return DomainService::class;
    }
}
