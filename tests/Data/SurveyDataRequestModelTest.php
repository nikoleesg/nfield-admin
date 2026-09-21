<?php

use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyDataRequestModel;

// Property names as defined by the API schema (openapi: NfieldPublicApi.Models.Surveys.SurveyDataRequestModel).
const SURVEY_DATA_REQUEST_KEYS = [
    'fileName', 'startDate', 'endDate', 'surveyVersion',
    'includeSuccessful', 'includeScreenOut', 'includeDroppedOut', 'includeRejected', 'includeTestData',
    'includeClosedAnswers', 'includeOpenAnswers', 'includeParaData',
    'includeCapturedMediaFiles', 'includeCapturedAudioSilentRecordingFiles', 'includeCapturedAudioQuestionFiles',
    'includeCapturedVideoQuestionFiles', 'includeCapturedPhotoQuestionFiles',
    'includeVarFile', 'includeQuestionnaireScript', 'includeAuditLog',
    'customColumnName', 'customColumnValue',
];

it('serialises exactly the keys defined by the API schema, in camelCase', function () {
    expect(array_keys(SurveyDataRequestModel::default()->toArray()))->toBe(SURVEY_DATA_REQUEST_KEYS);
});

it('accepts camelCase input keys', function () {
    $model = SurveyDataRequestModel::from(['fileName' => 'export', 'includeTestData' => true]);

    expect($model->fileName)->toBe('export')
        ->and($model->includeTestData)->toBeTrue();
});

it('accepts snake_case input keys', function () {
    $model = SurveyDataRequestModel::from(['file_name' => 'export', 'include_test_data' => true]);

    expect($model->fileName)->toBe('export')
        ->and($model->includeTestData)->toBeTrue();
});

it('keeps boolean defaults when keys are omitted from input', function () {
    $array = SurveyDataRequestModel::from(['fileName' => 'export'])->toArray();

    expect($array['includeSuccessful'])->toBeTrue()
        ->and($array['includeClosedAnswers'])->toBeTrue()
        ->and($array['includeTestData'])->toBeFalse()
        ->and($array['includeCapturedMediaFiles'])->toBeFalse();
});

it('builds a default request without throwing', function () {
    $model = SurveyDataRequestModel::default();

    expect($model->fileName)->toBeNull()
        ->and($model->startDate)->toBeNull()
        ->and($model->includeSuccessful)->toBeTrue();
});

it('casts and serialises dates as ISO 8601 date-time', function () {
    $model = SurveyDataRequestModel::from(['startDate' => '2024-01-02 03:04:05', 'endDate' => '2024-02-01']);

    expect($model->startDate)->toBeInstanceOf(Carbon::class)
        ->and($model->toArray()['startDate'])->toBe('2024-01-02T03:04:05+00:00')
        ->and($model->toArray()['endDate'])->toBe('2024-02-01T00:00:00+00:00');
});
