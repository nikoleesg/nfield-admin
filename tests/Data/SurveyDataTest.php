<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Data\SurveyData;
use Nikoleesg\NfieldAdmin\Enums\ChannelEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyStateEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyTypeEnum;

it('creates a basic survey from name and channel enum', function () {
    $survey = SurveyData::fromBasic('Test Survey', ChannelEnum::CAPI);

    expect($survey->survey_name)->toBe('Test Survey')
        ->and($survey->survey_type)->toBe(SurveyTypeEnum::FreeIntercept)
        ->and($survey->survey_state)->toBe(SurveyStateEnum::UnderConstruction)
        ->and($survey->survey_group_id)->toBe(1)
        ->and($survey->is_blueprint)->toBeFalse();
});

it('creates a basic survey from name and string channel', function () {
    $survey = SurveyData::fromBasic('Test Survey', 'Online');

    expect($survey->survey_type)->toBe(SurveyTypeEnum::Online);
});

it('defaults to online if invalid string channel is provided', function () {
    $survey = SurveyData::fromBasic('Test Survey', 'Invalid');

    expect($survey->survey_type)->toBe(SurveyTypeEnum::Online);
});
