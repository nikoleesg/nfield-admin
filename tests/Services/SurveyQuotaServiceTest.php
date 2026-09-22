<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaFrameEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaTargetsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaVersionsEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\Quota\QuotaFrameModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Quota\QuotaFrameVersionModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameEtagLevelTargetModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameEtagRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameEtagResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveyQuotaFrameRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveysQuotaFrameResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveysQuotaTargetsEtagResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveysQuotaTargetsResponseModel;
use Nikoleesg\NfieldAdmin\Services\SurveyQuotaService;

afterEach(function () {
    Mockery::close();
});

/**
 * The quota surface spans three endpoint classes since #40/#43 — frame,
 * targets and versions — behind one service. Each test names which.
 */
function quotaServiceWith(
    ?object $frame = null,
    ?object $targets = null,
    ?object $versions = null
): SurveyQuotaService {
    return (new SurveyQuotaService(
        $frame ?? Mockery::mock(SurveyQuotaFrameEndpointInterface::class),
        $targets ?? Mockery::mock(SurveyQuotaTargetsEndpointInterface::class),
        $versions ?? Mockery::mock(SurveyQuotaVersionsEndpointInterface::class),
    ))->setSurveyId('survey-1');
}

it('reads the quota frame', function () {
    $frame = Mockery::mock(SurveyQuotaFrameEndpointInterface::class);

    $frame->shouldReceive('getQuotaFrame')->with('survey-1')->once()->andReturn([
        'target' => 100,
        'variableDefinitions' => [],
        'frameVariables' => [],
        'id' => 'frame-1',
        'quotaETag' => 7,
    ]);

    $model = quotaServiceWith(frame: $frame)->getQuotaFrame();

    expect($model)->toBeInstanceOf(SurveysQuotaFrameResponseModel::class)
        ->and($model->quotaETag)->toBe(7);
});

it('writes the quota frame from an array or a request model', function () {
    $frame = Mockery::mock(SurveyQuotaFrameEndpointInterface::class);

    $frame->shouldReceive('setQuotaFrame')
        ->withArgs(fn (string $surveyId, array $payload) => $payload['target'] === 200)
        ->twice()
        ->andReturn([
            'target' => 200,
            'variableDefinitions' => [],
            'frameVariables' => [],
            'id' => 'frame-1',
            'quotaETag' => 8,
        ]);

    $service = quotaServiceWith(frame: $frame);

    expect($service->setQuotaFrame(['target' => 200, 'variableDefinitions' => [], 'frameVariables' => []])->target)->toBe(200)
        ->and($service->setQuotaFrame(new SurveyQuotaFrameRequestModel(200, [], []))->quotaETag)->toBe(8);
});

it('sets the level targets of a frame version', function () {
    $frame = Mockery::mock(SurveyQuotaFrameEndpointInterface::class);

    $levels = [['id' => 'level-1', 'target' => 10, 'maxTarget' => 20, 'maxOvershoot' => 0]];

    $frame->shouldReceive('setQuotaLevelsTargets')
        ->with('survey-1', '7', ['levels' => $levels])
        ->once()
        ->andReturn(['levels' => $levels]);

    // `::from()` rather than `new`: the `levels` property is documented as
    // `SurveyQuotaFrameEtagLevelTargetModel[]`, so Spatie only casts the rows
    // into models on the `from()` path.
    $response = quotaServiceWith(frame: $frame)
        ->setQuotaLevelsTargets('7', SurveyQuotaFrameEtagRequestModel::from(['levels' => $levels]));

    expect($response)->toBeInstanceOf(SurveyQuotaFrameEtagResponseModel::class)
        ->and($response->levels[0])->toBeInstanceOf(SurveyQuotaFrameEtagLevelTargetModel::class)
        ->and($response->levels[0]->target)->toBe(10)
        ->and($response->levels[0]->maxTarget)->toBe(20);
});

it('reads the quota targets, with and without an eTag', function () {
    $targets = Mockery::mock(SurveyQuotaTargetsEndpointInterface::class);

    $targets->shouldReceive('getQuotaTargets')->with('survey-1')->once()->andReturn([
        'id' => 'targets-1',
        'target' => 100,
        'rootLevelMaxOvershoot' => 5,
        'variables' => [[
            'id' => 'var-1',
            'name' => 'Gender',
            'isMulti' => false,
            'displayIndex' => 0,
            'levels' => [['id' => 'level-1', 'name' => 'Male', 'target' => 50]],
        ]],
    ]);

    $targets->shouldReceive('getQuotaTargetsByETag')->with('survey-1', 7)->once()->andReturn([
        'id' => 'targets-1',
        'target' => 100,
        'variables' => [],
        'successful' => 12,
    ]);

    $service = quotaServiceWith(targets: $targets);

    $current = $service->getQuotaTargets();

    expect($current)->toBeInstanceOf(SurveysQuotaTargetsResponseModel::class)
        ->and($current->variables[0]->levels[0]->name)->toBe('Male');

    $versioned = $service->getQuotaTargetsByETag(7);

    expect($versioned)->toBeInstanceOf(SurveysQuotaTargetsEtagResponseModel::class)
        ->and($versioned->successful)->toBe(12);
});

it('lists the quota versions and reads one by eTag', function () {
    $versions = Mockery::mock(SurveyQuotaVersionsEndpointInterface::class);

    $versions->shouldReceive('getQuotaVersions')->with('survey-1')->once()->andReturn([
        ['id' => 'version-1', 'eTag' => '7', 'publishedDate' => '2026-09-23T10:00:00Z'],
    ]);

    $versions->shouldReceive('getQuotaVersionsByETag')->with('survey-1', 7)->once()->andReturn([
        'id' => 'frame-1',
        'variables' => [],
        'target' => 100,
        'successful' => 3,
    ]);

    $service = quotaServiceWith(versions: $versions);

    $list = $service->getQuotaVersions();

    expect($list)->toBeInstanceOf(Collection::class)
        ->and($list->first())->toBeInstanceOf(QuotaFrameVersionModel::class)
        ->and($list->first()->publishedDate->format('Y-m-d'))->toBe('2026-09-23')
        ->and($service->getQuotaVersionsByETag(7))->toBeInstanceOf(QuotaFrameModel::class);
});
