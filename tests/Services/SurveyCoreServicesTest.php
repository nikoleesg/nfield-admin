<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyDataRetentionSettingsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyMoveEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyVersionsEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\GetDataRetentionSettingsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyMoveModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyVersionModel;
use Nikoleesg\NfieldAdmin\Data\UpdateDataRetentionSettingsModel;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;
use Nikoleesg\NfieldAdmin\Services\SurveyDataRetentionSettingsService;
use Nikoleesg\NfieldAdmin\Services\SurveyVersionsService;

afterEach(function () {
    Mockery::close();
});

it('reads and sets the data retention period from an int, an array or a model', function () {
    $endpoint = Mockery::mock(SurveyDataRetentionSettingsEndpointInterface::class);

    $endpoint->shouldReceive('get')->with('survey-1')->once()->andReturn([
        'retentionPeriod' => 90,
        'possibleValues' => [30, 90, 365],
    ]);
    $endpoint->shouldReceive('update')->with('survey-1', ['retentionPeriod' => 365])->times(3);

    $service = (new SurveyDataRetentionSettingsService($endpoint))->setSurveyId('survey-1');

    $settings = $service->get();

    expect($settings)->toBeInstanceOf(GetDataRetentionSettingsModel::class)
        ->and($settings->retentionPeriod)->toBe(90)
        ->and($settings->possibleValues)->toBe([30, 90, 365]);

    $service->update(365);
    $service->update(['retentionPeriod' => 365]);
    $service->update(new UpdateDataRetentionSettingsModel(365));
});

it('lists the published versions of the survey', function () {
    $endpoint = Mockery::mock(SurveyVersionsEndpointInterface::class);

    $endpoint->shouldReceive('list')->with('survey-1')->once()->andReturn([[
        'eTag' => '0x8DC',
        'publishDateUtc' => '2026-09-01T10:00:00Z',
        'nrOfSuccessfuls' => 120,
        'nrOfDroppedOuts' => 8,
        'nrOfScreenedOuts' => 30,
    ]]);

    $versions = (new SurveyVersionsService($endpoint))->setSurveyId('survey-1')->list();

    expect($versions)->toBeInstanceOf(Collection::class)
        ->and($versions->first())->toBeInstanceOf(SurveyVersionModel::class)
        ->and($versions->first()->eTag)->toBe('0x8DC')
        ->and($versions->first()->publishDateUtc)->toBeInstanceOf(Carbon::class)
        ->and($versions->first()->nrOfSuccessfuls)->toBe(120);
});

it('moves the survey it is scoped to into another group', function () {
    $move = Mockery::mock(SurveyMoveEndpointInterface::class);

    $move->shouldReceive('update')->with('survey-1', ['surveyGroupId' => 7])->once()->andReturn(['surveyGroupId' => 7]);

    app()->instance(SurveyEndpointInterface::class, Mockery::mock(SurveyEndpointInterface::class));
    app()->instance(SurveyMoveEndpointInterface::class, $move);

    $moved = (new SurveyResource)->setSurveyId('survey-1')->moveToGroup(7);

    expect($moved)->toBeInstanceOf(SurveyMoveModel::class)
        ->and($moved->surveyGroupId)->toBe(7);
});

it('reaches the new survey services with the survey scope', function () {
    $survey = (new SurveyResource)->setSurveyId('survey-1');

    expect($survey->dataRetentionSettings())->toBeInstanceOf(SurveyDataRetentionSettingsService::class)
        ->and($survey->dataRetentionSettings()->getSurveyId())->toBe('survey-1')
        ->and($survey->versions())->toBeInstanceOf(SurveyVersionsService::class)
        ->and($survey->versions()->getSurveyId())->toBe('survey-1');
});
