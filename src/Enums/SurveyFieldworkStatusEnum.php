<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Enums;

enum SurveyFieldworkStatusEnum: int
{
    case UnderConstruction = 0;
    case Started = 1;
    case Stopped = 3;
}
