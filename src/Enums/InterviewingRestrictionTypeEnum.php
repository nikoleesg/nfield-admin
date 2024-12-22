<?php

namespace Nikoleesg\NfieldAdmin\Enums;

enum InterviewingRestrictionTypeEnum: int
{
    case Unknown = 0;
    case BlockEverything = 1;
    case AllowOnlyActives = 2;
    case AllowActivesAndResumes = 3;

}
