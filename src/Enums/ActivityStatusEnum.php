<?php

namespace Nikoleesg\NfieldAdmin\Enums;

enum ActivityStatusEnum: int
{
    case Pending = 0;
    case Started = 1;
    case Successed = 2;
    case Failed = 3;
}
