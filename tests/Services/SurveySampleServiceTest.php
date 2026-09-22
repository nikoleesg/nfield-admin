<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleDataDownloadEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\ClearSurveySampleModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleFilterModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleUploadStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SurveyCreateSampleColumnModel;
use Nikoleesg\NfieldAdmin\Resources\SurveySampleResource;
use Nikoleesg\NfieldAdmin\Services\SurveySampleService;

afterEach(function () {
    Mockery::close();
});

function sampleServiceWith(
    ?object $collection = null,
    ?object $item = null,
    ?object $download = null
): SurveySampleService {
    return (new SurveySampleService(
        $collection ?? Mockery::mock(SurveySampleCollectionEndpointInterface::class),
        $item ?? Mockery::mock(SurveySampleEndpointInterface::class),
        $download ?? Mockery::mock(SurveySampleDataDownloadEndpointInterface::class),
    ))->setSurveyId('survey-1');
}

it('parses a downloaded sample into a collection of rows', function () {
    // A sample record has no fixed shape — its columns are defined per survey
    // — so it is a Collection of keyed rows rather than a DTO.
    $collection = Mockery::mock(SurveySampleCollectionEndpointInterface::class);

    $collection->shouldReceive('download')
        ->with('survey-1')
        ->once()
        ->andReturn("InterviewId\tName\tPhone\n1\tAda\t555\n2\tGrace\t556");

    $rows = sampleServiceWith(collection: $collection)->downloadSampleData();

    expect($rows)->toBeInstanceOf(Collection::class)
        ->and($rows)->toHaveCount(2)
        ->and($rows->first())->toBe(['InterviewId' => '1', 'Name' => 'Ada', 'Phone' => '555'])
        ->and($rows->last()['Name'])->toBe('Grace');
});

it('uploads sample data under a generated file name', function () {
    $collection = Mockery::mock(SurveySampleCollectionEndpointInterface::class);

    $collection->shouldReceive('upload')
        ->withArgs(function (string $surveyId, string $data, string $fileName) {
            return $surveyId === 'survey-1'
                && $data === "Name\nAda"
                && str_starts_with($fileName, 'Survey_survey-1_Samples_');
        })
        ->once()
        ->andReturn(['processingStatus' => 'Finished', 'totalRecordCount' => 1, 'insertedCount' => 1]);

    $status = sampleServiceWith(collection: $collection)->uploadSampleData("Name\nAda");

    expect($status)->toBeInstanceOf(SampleUploadStatus::class)
        ->and($status->insertedCount)->toBe(1);
});

it('uploads sample data under an explicit file name', function () {
    $collection = Mockery::mock(SurveySampleCollectionEndpointInterface::class);

    $collection->shouldReceive('upload')
        ->with('survey-1', "Name\nAda", 'mine.csv')
        ->once()
        ->andReturn(['processingStatus' => 'Finished']);

    expect(sampleServiceWith(collection: $collection)->uploadSampleData("Name\nAda", 'mine.csv')->processingStatus)
        ->toBe('Finished');
});

it('normalises filters through the model when blocking and resetting', function () {
    $collection = Mockery::mock(SurveySampleCollectionEndpointInterface::class);

    $expected = [
        ['name' => 'Status', 'op' => 'eq', 'value' => 'Open'],
        ['name' => 'Region', 'op' => 'eq', 'value' => 'North'],
    ];

    $collection->shouldReceive('block')->with('survey-1', $expected)->once()->andReturn(['activityId' => 'block-1']);
    $collection->shouldReceive('reset')->with('survey-1', $expected)->once()->andReturn(['activityId' => 'reset-1']);

    $service = sampleServiceWith(collection: $collection);

    // A mix of arrays and models goes in; one wire format comes out.
    $filters = [
        ['name' => 'Status', 'op' => 'eq', 'value' => 'Open'],
        new SampleFilterModel('Region', 'eq', 'North'),
    ];

    expect($service->blockSampleData($filters)->activityId)->toBe('block-1')
        ->and($service->resetSampleData($filters)->activityId)->toBe('reset-1');
});

it('creates sample columns and returns them as models', function () {
    $collection = Mockery::mock(SurveySampleCollectionEndpointInterface::class);

    $collection->shouldReceive('create')
        ->with('survey-1', [
            ['columnName' => 'Phone', 'value' => '555'],
            ['columnName' => 'Email', 'value' => 'ada@example.test'],
        ])
        ->once()
        ->andReturn([['columnName' => 'Phone', 'value' => '555']]);

    $columns = sampleServiceWith(collection: $collection)->createSampleData([
        ['columnName' => 'Phone', 'value' => '555'],
        new SurveyCreateSampleColumnModel('Email', 'ada@example.test'),
    ]);

    expect($columns)->toBeInstanceOf(Collection::class)
        ->and($columns->first())->toBeInstanceOf(SurveyCreateSampleColumnModel::class)
        ->and($columns->first()->columnName)->toBe('Phone');
});

it('clears sample columns', function () {
    $collection = Mockery::mock(SurveySampleCollectionEndpointInterface::class);

    $collection->shouldReceive('clear')
        ->withArgs(fn (string $surveyId, array $payload) => $payload['columns'] === ['Phone'])
        ->twice()
        ->andReturn(['activityId' => 'clear-1']);

    $service = sampleServiceWith(collection: $collection);

    expect($service->clearSampleDataColumns(['columns' => ['Phone']])->activityId)->toBe('clear-1')
        ->and($service->clearSampleDataColumns(new ClearSurveySampleModel(columns: ['Phone']))->activityId)->toBe('clear-1');
});

it('requests a sample download under a generated or explicit file name', function () {
    $download = Mockery::mock(SurveySampleDataDownloadEndpointInterface::class);

    $download->shouldReceive('requestDownload')
        ->withArgs(fn (string $surveyId, string $fileName) => str_starts_with($fileName, 'Survey_survey-1_Samples_'))
        ->once()
        ->andReturn(['activityId' => 'download-1']);

    $download->shouldReceive('requestDownload')
        ->with('survey-1', 'mine.csv')
        ->once()
        ->andReturn(['activityId' => 'download-2']);

    $service = sampleServiceWith(download: $download);

    expect($service->requestSampleDownload()->activityId)->toBe('download-1')
        ->and($service->requestSampleDownload('mine.csv')->activityId)->toBe('download-2');
});

it('hands its scope to the interview resource it returns', function () {
    $resource = sampleServiceWith()->forInterview(7);

    expect($resource)->toBeInstanceOf(SurveySampleResource::class)
        ->and($resource->getSurveyId())->toBe('survey-1');
});
