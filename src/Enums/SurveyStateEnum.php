<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Enums;

enum SurveyStateEnum: int
{
    case UnderConstruction = 0;
    case Started = 1;
    case Paused = 3;
}
