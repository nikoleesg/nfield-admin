<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersAssignmentsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersOfficesEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SubscriptionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyBlueprintsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleColumnUpdateModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleUpdateStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ReplaceSamplingPointWithSpareRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointUpdateRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyCountsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyUpdateModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\UpdateBlueprintModel;
use Nikoleesg\NfieldAdmin\Enums\BlueprintConfigurationEnum;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;
use Nikoleesg\NfieldAdmin\Resources\BlueprintSurveyResource;
use Nikoleesg\NfieldAdmin\Resources\CapiInterviewerResource;
use Nikoleesg\NfieldAdmin\Resources\EventSubscriptionResource;
use Nikoleesg\NfieldAdmin\Resources\SamplingPointResource;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;
use Nikoleesg\NfieldAdmin\Resources\SurveySampleResource;
use Nikoleesg\NfieldAdmin\Services\SamplingPointAddressCollectionService;
use Nikoleesg\NfieldAdmin\Services\SamplingPointAddressService;
use Nikoleesg\NfieldAdmin\Services\SamplingPointAssignmentService;
use Nikoleesg\NfieldAdmin\Services\SamplingPointQuotaTargetsService;

/**
 * #29: the fluent resources, against mocked endpoint contracts.
 *
 * `SurveyResourceTest` already covers the scope plumbing #41 introduced; this
 * covers what each resource does with the endpoints it holds.
 */
afterEach(function () {
    Mockery::close();
});

// ── SurveyResource ───────────────────────────────────────────────────────

/**
 * #55: the resource no longer holds the endpoint; its item operations go
 * through SurveyService, which the container builds with this mock.
 */
function surveyResourceWith(SurveyEndpointInterface $endpoint): SurveyResource
{
    app()->instance(SurveyEndpointInterface::class, $endpoint);

    return (new SurveyResource)->setSurveyId('survey-1');
}

it('reads, updates and deletes the survey it is scoped to', function () {
    $endpoint = Mockery::mock(SurveyEndpointInterface::class);

    $payload = surveyPayload();

    $endpoint->shouldReceive('get')->with('survey-1')->once()->andReturn($payload);
    $endpoint->shouldReceive('updatePartial')
        ->with('survey-1', ['surveyName' => 'Renamed'])
        ->once()
        ->andReturn($payload + ['surveyName' => 'Renamed']);
    $endpoint->shouldReceive('destroy')->with('survey-1')->once();

    $resource = surveyResourceWith($endpoint);

    expect($resource->get())->toBeInstanceOf(SurveyModel::class)
        ->and($resource->update(new SurveyUpdateModel(surveyName: 'Renamed'))->surveyName)->toBe('Demo');

    $resource->delete();
});

it('returns the survey counts as a model', function () {
    $endpoint = Mockery::mock(SurveyEndpointInterface::class);

    $endpoint->shouldReceive('counts')->with('survey-1')->once()->andReturn([
        'surveyId' => 'survey-1',
        'successfulCount' => 10,
        'screenedOutCount' => 1,
        'droppedOutCount' => 0,
        'rejectedCount' => 0,
        'quotaCounts' => null,
        'activeLiveCount' => 2,
        'activeTestCount' => 0,
    ]);

    $counts = surveyResourceWith($endpoint)->counts();

    expect($counts)->toBeInstanceOf(SurveyCountsModel::class)
        ->and($counts->successfulCount)->toBe(10)
        ->and($counts->activeLiveCount)->toBe(2);
});

it('returns the custom columns as a collection of names', function () {
    // `customColumns` returns an array of strings — the column names are
    // values, not keys, which is why they survive the key normalizer.
    $endpoint = Mockery::mock(SurveyEndpointInterface::class);

    $endpoint->shouldReceive('getCustomColumns')->with('survey-1')->once()->andReturn(['Phone', 'Email']);

    $columns = surveyResourceWith($endpoint)->customColumns();

    expect($columns)->toBeInstanceOf(Collection::class)
        ->and($columns->all())->toBe(['Phone', 'Email']);
});

// ── SamplingPointResource ────────────────────────────────────────────────

it('reads, updates, deletes, activates and replaces its sampling point', function () {
    $endpoint = Mockery::mock(SamplingPointEndpointInterface::class);

    $payload = samplingPointPayload();

    $endpoint->shouldReceive('get')->with('survey-1', 'sp-1')->once()->andReturn($payload);
    $endpoint->shouldReceive('delete')->with('survey-1', 'sp-1')->once();
    $endpoint->shouldReceive('update')
        ->with('survey-1', 'sp-1', ['name' => 'Renamed'])
        ->once()
        ->andReturn($payload);
    $endpoint->shouldReceive('activate')
        ->with('survey-1', 'sp-1', ['target' => 5])
        ->once()
        ->andReturn(['isActivated' => true]);
    $endpoint->shouldReceive('replace')
        ->with('survey-1', 'sp-1', ['spareSamplingPointId' => 'sp-9'])
        ->once()
        ->andReturn(['success' => true]);

    app()->instance(SamplingPointEndpointInterface::class, $endpoint);

    $resource = (new SamplingPointResource)
        ->setSurveyId('survey-1')
        ->setSamplingPointId('sp-1');

    expect($resource->get())->toBeInstanceOf(SamplingPointResponseModel::class)
        ->and($resource->update(new SamplingPointUpdateRequestModel(name: 'Renamed'))->name)->toBe('SP 1')
        ->and($resource->activate(['target' => 5])->isActivated)->toBeTrue()
        ->and($resource->replace(new ReplaceSamplingPointWithSpareRequestModel('sp-9'))->success)->toBeTrue();

    $resource->delete();
});

it('hands both scopes to every service it resolves', function () {
    $resource = (new SamplingPointResource)
        ->setSurveyId('survey-1')
        ->setSamplingPointId('sp-1');

    foreach ([
        'addresses' => SamplingPointAddressCollectionService::class,
        'assignments' => SamplingPointAssignmentService::class,
        'quotaTargets' => SamplingPointQuotaTargetsService::class,
    ] as $accessor => $class) {
        $service = $resource->{$accessor}();

        expect($service)->toBeInstanceOf($class)
            ->and($service->getSurveyId())->toBe('survey-1')
            ->and($service->getSamplingPointId())->toBe('sp-1')
            // The cache is keyed by the scope, so a second call is the same
            // instance until the scope changes.
            ->and($resource->{$accessor}())->toBe($service);
    }

    expect($resource->setSamplingPointId('sp-2')->addresses()->getSamplingPointId())->toBe('sp-2');
});

// ── SurveySampleResource ─────────────────────────────────────────────────

it('parses the single sample record it is scoped to', function () {
    $item = Mockery::mock(SurveySampleEndpointInterface::class);

    $item->shouldReceive('get')
        ->with('survey-1', 7)
        ->once()
        ->andReturn("InterviewId\tName\n7\tAda");

    $record = (new SurveySampleResource($item, Mockery::mock(SurveySampleCollectionEndpointInterface::class)))
        ->setSurveyId('survey-1')
        ->setInterviewId(7)
        ->get();

    expect($record)->toBeInstanceOf(Collection::class)
        ->and($record->all())->toBe(['InterviewId' => '7', 'Name' => 'Ada']);
});

it('returns null when the sample record is empty', function () {
    $item = Mockery::mock(SurveySampleEndpointInterface::class);

    $item->shouldReceive('get')->with('survey-1', 7)->once()->andReturn("InterviewId\tName\n");

    $record = (new SurveySampleResource($item, Mockery::mock(SurveySampleCollectionEndpointInterface::class)))
        ->setSurveyId('survey-1')
        ->setInterviewId(7)
        ->get();

    expect($record)->toBeNull();
});

it('updates the sample record of the interview it is scoped to', function () {
    // #71: the caller used to repeat the record ID inside the request model,
    // so forInterview() had no effect on the update. The scoped ID is now the
    // only source of sampleRecordId.
    $collection = Mockery::mock(SurveySampleCollectionEndpointInterface::class);

    $collection->shouldReceive('update')
        ->with('survey-1', [
            'sampleRecordId' => 7,
            'columnUpdates' => [
                ['columnName' => 'Phone', 'value' => '555'],
                ['columnName' => 'Region', 'value' => 'North'],
            ],
        ])
        ->once()
        ->andReturn(['resultStatus' => true]);

    $updated = (new SurveySampleResource(Mockery::mock(SurveySampleEndpointInterface::class), $collection))
        ->setSurveyId('survey-1')
        ->setInterviewId(7)
        ->update([
            ['columnName' => 'Phone', 'value' => '555'],
            new SampleColumnUpdateModel('Region', 'North'),
        ]);

    expect($updated)->toBeInstanceOf(SampleUpdateStatus::class)
        ->and($updated->resultStatus)->toBeTrue();
});

it('refuses a record update before the interview scope is set', function () {
    $resource = (new SurveySampleResource(
        Mockery::mock(SurveySampleEndpointInterface::class),
        Mockery::mock(SurveySampleCollectionEndpointInterface::class),
    ))->setSurveyId('survey-1');

    expect(fn () => $resource->update([['columnName' => 'Phone', 'value' => '555']]))
        ->toThrow(MissingScopeException::class);
});

it('refuses a sample call before the survey scope is set', function () {
    $resource = (new SurveySampleResource(
        Mockery::mock(SurveySampleEndpointInterface::class),
        Mockery::mock(SurveySampleCollectionEndpointInterface::class),
    ))->setInterviewId(7);

    expect(fn () => $resource->get())->toThrow(MissingScopeException::class);
});

// ── BlueprintSurveyResource ──────────────────────────────────────────────

it('updates a blueprint with its configuration flag', function () {
    $endpoint = Mockery::mock(SurveyBlueprintsEndpointInterface::class);

    $endpoint->shouldReceive('update')
        ->with('bp-1', [
            'surveyId' => 'survey-1',
            'includedConfiguration' => BlueprintConfigurationEnum::All->value,
        ])
        ->twice();

    $resource = (new BlueprintSurveyResource($endpoint))->setBlueprintId('bp-1');

    $resource->update(['surveyId' => 'survey-1']);
    $resource->update(new UpdateBlueprintModel('survey-1'));
});

// ── Unset item scope (#55) ───────────────────────────────────────────────

it('refuses an item call before its own scope is set', function (Closure $call) {
    // The endpoints are strict mocks: reaching one would fail the test with a
    // Mockery error, so the exception proves the guard fired first.
    expect($call)->toThrow(MissingScopeException::class);
})->with([
    'sample record without interviewId' => fn () => (new SurveySampleResource(
        Mockery::mock(SurveySampleEndpointInterface::class),
        Mockery::mock(SurveySampleCollectionEndpointInterface::class),
    ))->setSurveyId('survey-1')->get(),
    'blueprint without blueprintId' => fn () => (new BlueprintSurveyResource(
        Mockery::mock(SurveyBlueprintsEndpointInterface::class),
    ))->update(['surveyId' => 'survey-1']),
    'address without addressId' => fn () => (new SamplingPointAddressService(
        Mockery::mock(SamplingPointAddressEndpointInterface::class),
    ))->setSurveyId('survey-1')->setSamplingPointId('sp-1')->get(),
    'CAPI interviewer without interviewerId' => fn () => (new CapiInterviewerResource(
        Mockery::mock(CapiInterviewersEndpointInterface::class),
        Mockery::mock(CapiInterviewersAssignmentsEndpointInterface::class),
        Mockery::mock(CapiInterviewersOfficesEndpointInterface::class),
    ))->get(),
    'event subscription without a name' => fn () => (new EventSubscriptionResource(
        Mockery::mock(SubscriptionEndpointInterface::class),
    ))->delete(),
]);
