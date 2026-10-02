<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Mockery\MockInterface;
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
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;
use Nikoleesg\NfieldAdmin\Resources\SurveyQuotaResource;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;
use Nikoleesg\NfieldAdmin\Services\SurveyQuotaFrameService;
use Nikoleesg\NfieldAdmin\Services\SurveyQuotaTargetsService;
use Nikoleesg\NfieldAdmin\Services\SurveyQuotaVersionService;
use Nikoleesg\NfieldAdmin\Services\SurveyQuotaVersionsService;
use Nikoleesg\NfieldAdmin\Services\SurveyQuotaVersionTargetsService;

afterEach(function () {
    Mockery::close();
});

/**
 * #69: the frame, targets and versions are three API resources, so each has
 * its own service behind `quota()`. Tests go through the real navigation with
 * the endpoints bound as mocks, so the scope hand-off is exercised too.
 */
function quotaFor(
    ?object $frame = null,
    ?object $targets = null,
    ?object $versions = null
): SurveyQuotaResource {
    app()->instance(SurveyQuotaFrameEndpointInterface::class, $frame ?? Mockery::mock(SurveyQuotaFrameEndpointInterface::class));
    app()->instance(SurveyQuotaTargetsEndpointInterface::class, $targets ?? Mockery::mock(SurveyQuotaTargetsEndpointInterface::class));
    app()->instance(SurveyQuotaVersionsEndpointInterface::class, $versions ?? Mockery::mock(SurveyQuotaVersionsEndpointInterface::class));

    return (new SurveyQuotaResource)->setSurveyId('survey-1');
}

it('navigates to one service per quota resource', function () {
    $quota = quotaFor();

    expect($quota->frame())->toBeInstanceOf(SurveyQuotaFrameService::class)
        ->and($quota->targets())->toBeInstanceOf(SurveyQuotaTargetsService::class)
        ->and($quota->versions())->toBeInstanceOf(SurveyQuotaVersionsService::class)
        ->and($quota->targets()->forVersion('7'))->toBeInstanceOf(SurveyQuotaVersionTargetsService::class)
        ->and($quota->versions()->forVersion('7'))->toBeInstanceOf(SurveyQuotaVersionService::class)
        ->and($quota->versions()->forVersion('7')->getQuotaVersion())->toBe('7')
        ->and($quota->versions()->forVersion('7')->getSurveyId())->toBe('survey-1');
});

it('is reached from the survey with the survey scope handed on', function () {
    $quota = (new SurveyResource)
        ->setSurveyId('survey-9')
        ->quota();

    expect($quota)->toBeInstanceOf(SurveyQuotaResource::class)
        ->and($quota->getSurveyId())->toBe('survey-9')
        ->and($quota->targets()->forVersion('3')->getSurveyId())->toBe('survey-9');
});

it('refuses a version call before the version scope is set', function () {
    $service = (new SurveyQuotaVersionService(Mockery::mock(SurveyQuotaVersionsEndpointInterface::class)))
        ->setSurveyId('survey-1');

    expect(fn () => $service->get())->toThrow(MissingScopeException::class);
});

it('reads the quota frame', function () {
    /** @var SurveyQuotaFrameEndpointInterface&MockInterface $frame */
    $frame = Mockery::mock(SurveyQuotaFrameEndpointInterface::class);

    $frame->shouldReceive('get')->with('survey-1')->once()->andReturn([
        'target' => 100,
        'variableDefinitions' => [],
        'frameVariables' => [],
        'id' => 'frame-1',
        'quotaETag' => 7,
    ]);

    $model = quotaFor(frame: $frame)->frame()->get();

    expect($model)->toBeInstanceOf(SurveysQuotaFrameResponseModel::class)
        ->and($model->quotaETag)->toBe(7);
});

it('writes the quota frame from an array or a request model', function () {
    /** @var SurveyQuotaFrameEndpointInterface&MockInterface $frame */
    $frame = Mockery::mock(SurveyQuotaFrameEndpointInterface::class);

    $frame->shouldReceive('update')
        ->withArgs(fn (string $surveyId, array $payload) => $payload['target'] === 200)
        ->twice()
        ->andReturn([
            'target' => 200,
            'variableDefinitions' => [],
            'frameVariables' => [],
            'id' => 'frame-1',
            'quotaETag' => 8,
        ]);

    $service = quotaFor(frame: $frame)->frame();

    expect($service->update(['target' => 200, 'variableDefinitions' => [], 'frameVariables' => []])->target)->toBe(200)
        ->and($service->update(new SurveyQuotaFrameRequestModel(200, [], []))->quotaETag)->toBe(8);
});

it('updates the targets of a frame version', function () {
    /** @var SurveyQuotaFrameEndpointInterface&MockInterface $frame */
    $frame = Mockery::mock(SurveyQuotaFrameEndpointInterface::class);

    $levels = [['id' => 'level-1', 'target' => 10, 'maxTarget' => 20, 'maxOvershoot' => 0]];

    $frame->shouldReceive('updateVersion')
        ->with('survey-1', '7', ['levels' => $levels])
        ->once()
        ->andReturn(['levels' => $levels]);

    // `::from()` rather than `new`: the `levels` property is documented as
    // `SurveyQuotaFrameEtagLevelTargetModel[]`, so Spatie only casts the rows
    // into models on the `from()` path.
    $response = quotaFor(frame: $frame)->targets()->forVersion('7')
        ->update(SurveyQuotaFrameEtagRequestModel::from(['levels' => $levels]));

    expect($response)->toBeInstanceOf(SurveyQuotaFrameEtagResponseModel::class)
        ->and($response->levels[0])->toBeInstanceOf(SurveyQuotaFrameEtagLevelTargetModel::class)
        ->and($response->levels[0]->target)->toBe(10)
        ->and($response->levels[0]->maxTarget)->toBe(20);
});

it('reads the current targets and the targets of one version', function () {
    /** @var SurveyQuotaTargetsEndpointInterface&MockInterface $targets */
    $targets = Mockery::mock(SurveyQuotaTargetsEndpointInterface::class);

    $targets->shouldReceive('get')->with('survey-1')->once()->andReturn([
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

    $targets->shouldReceive('getVersion')->with('survey-1', '7')->once()->andReturn([
        'id' => 'targets-1',
        'target' => 100,
        'variables' => [],
        'successful' => 12,
    ]);

    $service = quotaFor(targets: $targets)->targets();

    $current = $service->get();

    expect($current)->toBeInstanceOf(SurveysQuotaTargetsResponseModel::class)
        ->and($current->variables[0]->levels[0]->name)->toBe('Male');

    $versioned = $service->forVersion('7')->get();

    expect($versioned)->toBeInstanceOf(SurveysQuotaTargetsEtagResponseModel::class)
        ->and($versioned->successful)->toBe(12);
});

it('lists the quota versions and reads one by its eTag', function (?string $publishedDate, ?string $expectedDate) {
    /** @var SurveyQuotaVersionsEndpointInterface&MockInterface $versions */
    $versions = Mockery::mock(SurveyQuotaVersionsEndpointInterface::class);

    $versions->shouldReceive('list')->with('survey-1')->once()->andReturn([
        ['id' => 'version-1', 'eTag' => '7', 'publishedDate' => $publishedDate],
    ]);

    $versions->shouldReceive('get')->with('survey-1', '7')->once()->andReturn([
        'id' => 'frame-1',
        'variables' => [],
        'target' => 100,
        'successful' => 3,
    ]);

    $service = quotaFor(versions: $versions)->versions();

    $list = $service->list();

    expect($list)->toBeInstanceOf(Collection::class)
        ->and($list->first())->toBeInstanceOf(QuotaFrameVersionModel::class)
        ->and($list->first()->publishedDate?->format('Y-m-d\TH:i:s.uP'))->toBe($expectedDate)
        ->and($service->forVersion($list->first()->eTag)->get())->toBeInstanceOf(QuotaFrameModel::class);
})->with([
    'whole seconds' => ['2026-09-23T10:00:00Z', '2026-09-23T10:00:00.000000+00:00'],
    'microseconds' => ['2026-01-13T07:28:21.725733Z', '2026-01-13T07:28:21.725733+00:00'],
    'milliseconds' => ['2026-01-13T07:28:21.725Z', '2026-01-13T07:28:21.725000+00:00'],
    'timezone offset' => ['2026-01-13T07:28:21.725733+08:00', '2026-01-13T07:28:21.725733+08:00'],
    'null date' => [null, null],
]);
