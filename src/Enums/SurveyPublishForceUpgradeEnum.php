<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Enums;

enum SurveyPublishForceUpgradeEnum: int
{
    case NoUpgrade = 0;
    case ForceUpgrade = 1;
}
