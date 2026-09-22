<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Enums;

enum SurveySettingNameEnum: string
{
    case LocationCaptureGps = 'LocationCaptureGps';
    case LocationTracking = 'LocationTracking';
    case LocationContinuousTracking = 'LocationContinuousTracking';
    case LocationValidation = 'LocationValidation';
    case MediaFilesAutomaticSynchronization = 'MediaFilesAutomaticSynchronization';
    case OpenAnswersAutomaticSynchronization = 'OpenAnswersAutomaticSynchronization';
    case AutomaticSynchronization = 'AutomaticSynchronization';
    case Orientation = 'Orientation';
    case LocationAccuracy = 'LocationAccuracy';
    case LocationWaitTime = 'LocationWaitTime';
    case AllowOnlyKnownRespondents = 'AllowOnlyKnownRespondents';
    case HideQuotaPage = 'HideQuotaPage';
}
