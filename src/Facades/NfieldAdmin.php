<?php

namespace Nikoleesg\NfieldAdmin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Nikoleesg\NfieldAdmin\NfieldAdmin
 */
class NfieldAdmin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Nikoleesg\NfieldAdmin\NfieldAdmin::class;
    }
}
