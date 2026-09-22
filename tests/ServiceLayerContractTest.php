<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyFieldworkEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGeneralSettingsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaFrameEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaTargetsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaVersionsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleDataDownloadEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySettingsEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\Quota\QuotaFrameVersionModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\ClearSurveySampleModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleFilterModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointCreateRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyFieldwork\SurveyFieldworkCountsResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveysQuotaTargetsResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveySettingModel;
use Nikoleesg\NfieldAdmin\Enums\InterviewingRestrictionTypeEnum;
use Nikoleesg\NfieldAdmin\Enums\SamplingPointKindEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyFieldworkStatusEnum;
use Nikoleesg\NfieldAdmin\Services\SamplingPointService;
use Nikoleesg\NfieldAdmin\Services\SurveyFieldworkService;
use Nikoleesg\NfieldAdmin\Services\SurveyQuotaService;
use Nikoleesg\NfieldAdmin\Services\SurveySampleService;
use Nikoleesg\NfieldAdmin\Services\SurveySettingsService;

afterEach(function () {
    Mockery::close();
});

/**
 * Fixtures handed to a mocked endpoint are camelCase: endpoints sit above the
 * normalisation boundary, so that is what the real ones return (#36).
 */
function fieldworkCountsPayload(): array
{
    return [
        'surveyId' => 'survey-1',
        'successful' => 12,
        'successfulLast24Hours' => 4,
        'screenedOut' => 3,
        'droppedOut' => 1,
        'rejected' => 0,
        'successfulDeleted' => 0,
        'screenedOutDeleted' => 0,
        'droppedOutDeleted' => 0,
        'rejectedDeleted' => 0,
        'activeInterviews' => 2,
        'screenedOutOverview' => [
            ['responseCode' => 21, 'count' => 3],
        ],
    ];
}

// ============================================================
// #34 — fieldwork enums
// ============================================================

it('returns the fieldwork status as an enum', function () {
    $endpoint = Mockery::mock(SurveyFieldworkEndpointInterface::class);
    $endpoint->shouldReceive('status')->with('survey-1')->once()->andReturn(3);

    $service = (new SurveyFieldworkService($endpoint))->setSurveyId('survey-1');

    expect($service->status())->toBe(SurveyFieldworkStatusEnum::Stopped);
});

it('returns null rather than throwing on an unmapped fieldwork status', function () {
    $endpoint = Mockery::mock(SurveyFieldworkEndpointInterface::class);
    $endpoint->shouldReceive('status')->with('survey-1')->twice()->andReturn(2);

    $service = (new SurveyFieldworkService($endpoint))->setSurveyId('survey-1');

    expect($service->status())->toBeNull()
        ->and($service->statusCode())->toBe(2);
});

it('builds the stop payload from an interviewing restriction enum', function () {
    $endpoint = Mockery::mock(SurveyFieldworkEndpointInterface::class);
    $endpoint->shouldReceive('stop')
        ->with('survey-1', ['interviewingRestrictionType' => 2])
        ->once();

    $service = (new SurveyFieldworkService($endpoint))->setSurveyId('survey-1');

    $service->stop(InterviewingRestrictionTypeEnum::AllowOnlyActives);
});

it('accepts an array for the stop payload', function () {
    $endpoint = Mockery::mock(SurveyFieldworkEndpointInterface::class);
    $endpoint->shouldReceive('stop')
        ->with('survey-1', ['interviewingRestrictionType' => 3])
        ->once();

    $service = (new SurveyFieldworkService($endpoint))->setSurveyId('survey-1');

    $service->stop(['interviewingRestrictionType' => 3]);
});

it('returns fieldwork counts as a DTO', function () {
    $endpoint = Mockery::mock(SurveyFieldworkEndpointInterface::class);
    $endpoint->shouldReceive('counts')->with('survey-1')->once()->andReturn(fieldworkCountsPayload());

    $counts = (new SurveyFieldworkService($endpoint))->setSurveyId('survey-1')->counts();

    expect($counts)->toBeInstanceOf(SurveyFieldworkCountsResponseModel::class)
        ->and($counts->successful)->toBe(12)
        ->and($counts->screenedOutOverview[0]->responseCode)->toBe(21);
});

// ============================================================
// #38 — Illuminate Collection everywhere
// ============================================================

it('returns survey settings as an Illuminate collection of DTOs', function () {
    $settingsEndpoint = Mockery::mock(SurveySettingsEndpointInterface::class);
    $generalEndpoint = Mockery::mock(SurveyGeneralSettingsEndpointInterface::class);

    $settingsEndpoint->shouldReceive('listSettings')->with('survey-1')->once()->andReturn([
        ['name' => 'Foo', 'value' => 'bar'],
    ]);

    $result = (new SurveySettingsService($settingsEndpoint, $generalEndpoint))->setSurveyId('survey-1')->list();

    expect($result)->toBeInstanceOf(Collection::class)
        ->and($result->first())->toBeInstanceOf(SurveySettingModel::class)
        ->and($result->first()->value)->toBe('bar');
});

// ============================================================
// #33/#37 — array|RequestModel in, DTO out
// ============================================================

it('normalises a sampling point create payload through the request model', function () {
    $collectionEndpoint = Mockery::mock(SamplingPointCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(SamplingPointEndpointInterface::class);
    $surveyEndpoint = Mockery::mock(SurveyEndpointInterface::class);

    $collectionEndpoint->shouldReceive('create')
        ->with('survey-1', Mockery::on(fn ($arg) => is_array($arg)
            && $arg['name'] === 'SP 1'
            && $arg['kind'] === 1
            && array_key_exists('stratum', $arg)))
        ->once()
        ->andReturn(['name' => 'SP 1', 'samplingPointId' => 'sp-1', 'kind' => 1]);

    $service = (new SamplingPointService($collectionEndpoint, $endpoint, $surveyEndpoint))->setSurveyId('survey-1');

    $result = $service->createSamplingPoint(['name' => 'SP 1', 'kind' => 1]);

    expect($result)->toBeInstanceOf(SamplingPointResponseModel::class)
        ->and($result->samplingPointId)->toBe('sp-1')
        ->and($result->kind)->toBe(SamplingPointKindEnum::Spare);
});

it('accepts a request model as well as an array', function () {
    $collectionEndpoint = Mockery::mock(SamplingPointCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(SamplingPointEndpointInterface::class);
    $surveyEndpoint = Mockery::mock(SurveyEndpointInterface::class);

    $collectionEndpoint->shouldReceive('create')
        ->with('survey-1', Mockery::on(fn ($arg) => is_array($arg) && $arg['name'] === 'SP 2'))
        ->once()
        ->andReturn(['name' => 'SP 2', 'samplingPointId' => 'sp-2']);

    $service = (new SamplingPointService($collectionEndpoint, $endpoint, $surveyEndpoint))->setSurveyId('survey-1');

    $result = $service->createSamplingPoint(new SamplingPointCreateRequestModel(name: 'SP 2'));

    expect($result->samplingPointId)->toBe('sp-2');
});

it('lists sampling points as a collection of DTOs', function () {
    $collectionEndpoint = Mockery::mock(SamplingPointCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(SamplingPointEndpointInterface::class);
    $surveyEndpoint = Mockery::mock(SurveyEndpointInterface::class);

    $collectionEndpoint->shouldReceive('find')->with('survey-1', [])->once()->andReturn([
        ['name' => 'SP 1', 'samplingPointId' => 'sp-1'],
        ['name' => 'SP 2', 'samplingPointId' => 'sp-2'],
    ]);

    $service = (new SamplingPointService($collectionEndpoint, $endpoint, $surveyEndpoint))->setSurveyId('survey-1');

    $result = $service->listSamplingPoints();

    expect($result)->toBeInstanceOf(Collection::class)
        ->and($result)->toHaveCount(2)
        ->and($result->first())->toBeInstanceOf(SamplingPointResponseModel::class);
});

// ============================================================
// #33 — SampleFilterModel is no longer an empty stub
// ============================================================

function sampleService(SurveySampleCollectionEndpointInterface $collectionEndpoint): SurveySampleService
{
    return (new SurveySampleService(
        $collectionEndpoint,
        Mockery::mock(SurveySampleEndpointInterface::class),
        Mockery::mock(SurveySampleDataDownloadEndpointInterface::class),
    ))->setSurveyId('survey-1');
}

it('sends sample filters as a list of name/op/value clauses', function () {
    $collectionEndpoint = Mockery::mock(SurveySampleCollectionEndpointInterface::class);

    $collectionEndpoint->shouldReceive('block')
        ->with('survey-1', [['name' => 'Email', 'op' => 'eq', 'value' => 'a@b.c']])
        ->once()
        ->andReturn(['activityId' => 'act-1']);

    $result = sampleService($collectionEndpoint)->blockSampleData([
        ['name' => 'Email', 'op' => 'eq', 'value' => 'a@b.c'],
    ]);

    expect($result)->toBeInstanceOf(BackgroundActivityStatus::class)
        ->and($result->activityId)->toBe('act-1');
});

it('accepts SampleFilterModel instances for a reset', function () {
    $collectionEndpoint = Mockery::mock(SurveySampleCollectionEndpointInterface::class);

    $collectionEndpoint->shouldReceive('reset')
        ->with('survey-1', [['name' => 'Status', 'op' => 'neq', 'value' => '1']])
        ->once()
        ->andReturn(['activityId' => 'act-2']);

    $result = sampleService($collectionEndpoint)->resetSampleData([
        new SampleFilterModel('Status', 'neq', '1'),
    ]);

    expect($result->activityId)->toBe('act-2');
});

it('nests sample filters inside the clear request model', function () {
    $collectionEndpoint = Mockery::mock(SurveySampleCollectionEndpointInterface::class);

    $collectionEndpoint->shouldReceive('clear')
        ->with('survey-1', [
            'filters' => [['name' => 'Status', 'op' => 'eq', 'value' => '2']],
            'columns' => ['Email'],
        ])
        ->once()
        ->andReturn(['activityId' => 'act-3']);

    $result = sampleService($collectionEndpoint)->clearSampleDataColumns(new ClearSurveySampleModel(
        filters: [new SampleFilterModel('Status', 'eq', '2')],
        columns: ['Email'],
    ));

    expect($result->activityId)->toBe('act-3');
});

// ============================================================
// #33/#37 — quota targets and versions
// ============================================================

function quotaService(
    SurveyQuotaTargetsEndpointInterface $targetsEndpoint,
    SurveyQuotaVersionsEndpointInterface $versionsEndpoint,
): SurveyQuotaService {
    return (new SurveyQuotaService(
        Mockery::mock(SurveyQuotaFrameEndpointInterface::class),
        $targetsEndpoint,
        $versionsEndpoint,
    ))->setSurveyId('survey-1');
}

it('returns quota targets as a DTO', function () {
    $targetsEndpoint = Mockery::mock(SurveyQuotaTargetsEndpointInterface::class);
    $versionsEndpoint = Mockery::mock(SurveyQuotaVersionsEndpointInterface::class);

    $targetsEndpoint->shouldReceive('getQuotaTargets')->with('survey-1')->once()->andReturn([
        'id' => 'frame-1',
        'target' => 100,
        'rootLevelMaxOvershoot' => 5,
        'variables' => [
            [
                'id' => 'var-1',
                'name' => 'Gender',
                'isMulti' => false,
                'displayIndex' => 0,
                'levels' => [
                    ['id' => 'lvl-1', 'name' => 'Male', 'variables' => null, 'target' => 50, 'maxTarget' => null, 'maxOvershoot' => null, 'displayIndex' => 0],
                ],
            ],
        ],
    ]);

    $result = quotaService($targetsEndpoint, $versionsEndpoint)->getQuotaTargets();

    expect($result)->toBeInstanceOf(SurveysQuotaTargetsResponseModel::class)
        ->and($result->rootLevelMaxOvershoot)->toBe(5)
        ->and($result->variables[0]->levels[0]->target)->toBe(50);
});

it('returns quota versions as a collection of DTOs', function () {
    $targetsEndpoint = Mockery::mock(SurveyQuotaTargetsEndpointInterface::class);
    $versionsEndpoint = Mockery::mock(SurveyQuotaVersionsEndpointInterface::class);

    $versionsEndpoint->shouldReceive('getQuotaVersions')->with('survey-1')->once()->andReturn([
        ['id' => 'q-1', 'eTag' => '1', 'publishedDate' => '2026-01-02T03:04:05Z'],
    ]);

    $result = quotaService($targetsEndpoint, $versionsEndpoint)->getQuotaVersions();

    expect($result)->toBeInstanceOf(Collection::class)
        ->and($result->first())->toBeInstanceOf(QuotaFrameVersionModel::class)
        ->and($result->first()->publishedDate->year)->toBe(2026);
});
