<?php

namespace Nikoleesg\NfieldAdmin\Enums;

enum BlueprintConfigurationEnum: int
{
    case None = 0;
    case QuotaFrame = 1;
    case QuestionnaireScript = 2;
    case All = -1;
}
