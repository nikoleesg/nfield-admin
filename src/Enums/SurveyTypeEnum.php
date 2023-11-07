<?php

namespace Nikoleesg\NfieldAdmin\Enums;

enum SurveyTypeEnum: string
{
    case Online = 'OnlineBasic';
    case FreeIntercept = 'Basic';
    //    case JointTargets = 'Basic';
    //    case IndividualTargets = 'Basic';
    case SamplingPointsWithQuota = 'Advanced';
    case SamplingPointsWithAddresses = 'EuroBarometer';
    case SamplingPointsWithQuotaAndQuota = 'EuroBarometerAdvanced';

    public function channel(): string
    {
        return match ($this) {
            SurveyTypeEnum::Online => 'Online',
            SurveyTypeEnum::FreeIntercept => 'CAPI',
            SurveyTypeEnum::JointTargets => 'CAPI',
            SurveyTypeEnum::IndividualTargets => 'CAPI',
            SurveyTypeEnum::SamplingPointsWithQuota => 'CAPI',
            SurveyTypeEnum::SamplingPointsWithAddresses => 'CAPI',
            SurveyTypeEnum::SamplingPointsWithQuotaAndQuota => 'CAPI',
        };
    }
}
