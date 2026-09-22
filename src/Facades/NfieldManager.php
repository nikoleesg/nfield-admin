<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Facades;

use Illuminate\Support\Facades\Facade;

class NfieldManager extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'nfield-manager';
    }
}
