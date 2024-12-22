<?php

namespace Nikoleesg\NfieldAdmin\Enums;

enum SurveyPublishStateEnum: int
{
    case Unknown = 0;
    case Unpublished = 1;
    case PublishedLatest = 2;
    case PublishedOutdated = 3;
}
