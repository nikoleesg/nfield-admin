<?php

namespace Nikoleesg\NfieldAdmin\Facades;

use Illuminate\Support\Facades\Facade;
use Nikoleesg\NfieldAdmin\Services\InterviewerService;

class Interviewer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return InterviewerService::class;
    }
}
