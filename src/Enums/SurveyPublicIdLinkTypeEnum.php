<?php

namespace Nikoleesg\NfieldAdmin\Enums;

enum SurveyPublicIdLinkTypeEnum: string
{
    case LiveId = 'LiveId';
    case ExternalTestId = 'ExternalTestId';
    case InternalTestId = 'InternalTestId';
}
