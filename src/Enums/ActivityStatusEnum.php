<?php

namespace Nikoleesg\NfieldAdmin\Enums;

enum ActivityStatusEnum: int
{
    case Pending = 0;
    case Started = 1;
    case Succeeded = 2;
    case Failed = 3;
    case Cancelled = 4;
}
