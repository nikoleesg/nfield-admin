<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Enums\SurveyTypeEnum;

it('returns the correct channel for each survey type', function () {
    expect(SurveyTypeEnum::Online->channel())->toBe('Online')
        ->and(SurveyTypeEnum::FreeIntercept->channel())->toBe('CAPI')
        ->and(SurveyTypeEnum::SamplingPointsWithQuota->channel())->toBe('CAPI')
        ->and(SurveyTypeEnum::SamplingPointsWithAddresses->channel())->toBe('CAPI')
        ->and(SurveyTypeEnum::SamplingPointsWithQuotaAndQuota->channel())->toBe('CAPI');
});
