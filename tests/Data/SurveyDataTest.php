<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Data\SurveyData;
use Nikoleesg\NfieldAdmin\Enums\ChannelEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyStateEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyTypeEnum;

it('creates a basic survey from name and channel enum', function () {
    $survey = SurveyData::fromBasic('Test Survey', ChannelEnum::CAPI);

    expect($survey->surveyName)->toBe('Test Survey')
        ->and($survey->surveyType)->toBe(SurveyTypeEnum::FreeIntercept)
        ->and($survey->surveyState)->toBe(SurveyStateEnum::UnderConstruction)
        ->and($survey->surveyGroupId)->toBe(1)
        ->and($survey->isBlueprint)->toBeFalse();
});

it('creates a basic survey from name and string channel', function () {
    $survey = SurveyData::fromBasic('Test Survey', 'Online');

    expect($survey->surveyType)->toBe(SurveyTypeEnum::Online);
});

it('defaults to online if invalid string channel is provided', function () {
    $survey = SurveyData::fromBasic('Test Survey', 'Invalid');

    expect($survey->surveyType)->toBe(SurveyTypeEnum::Online);
});
