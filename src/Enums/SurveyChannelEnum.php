<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Enums;

enum SurveyChannelEnum: int
{
    case Unknown = 0;
    case Cati = 1;
    case Online = 2;
    case Capi = 3;
}
