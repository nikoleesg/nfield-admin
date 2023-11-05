<?php

namespace Nikoleesg\NfieldAdmin\Enums;

enum SamplingPointKindEnum: int
{
    case Regular = 0;
    case Spare = 1;
    case SpareActive = 2;
    case Replaced = 3;
}
