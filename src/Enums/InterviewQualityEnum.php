<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Enums;

enum InterviewQualityEnum: int
{
    case NotChecked = 0;
    case Approved = 1;
    case Unverified = 2;
    case Rejected = 3;
    case MarkedToReject = 4;
    case ToBeChecked = 5;
}
