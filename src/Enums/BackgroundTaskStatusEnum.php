<?php

namespace Nikoleesg\NfieldAdmin\Enums;

enum BackgroundTaskStatusEnum: int
{
    case Initialized = 0;
    case Running = 1;
    case Canceled = 2;
    case Exception = 3;
    case Completed = 4;
}
