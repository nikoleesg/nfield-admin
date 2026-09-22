<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyBlueprintsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleFilterModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleUpdateStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SurveyUpdateSampleRecordModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\Addresses\AddressModel;
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
use Nikoleesg\NfieldAdmin\Resources\SamplingPointAddressResource;
use Nikoleesg\NfieldAdmin\Resources\SamplingPointResource;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;
use Nikoleesg\NfieldAdmin\Resources\SurveySampleResource;
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

it('reads, updates and deletes the survey it is scoped to', function () {
    $endpoint = Mockery::mock(SurveyEndpointInterface::class);

    $payload = surveyPayload();

    $endpoint->shouldReceive('get')->with('survey-1')->once()->andReturn($payload);
    $endpoint->shouldReceive('updatePartial')
        ->withArgs(fn (string $surveyId, array $body) => $body['surveyName'] === 'Renamed')
        ->once()
        ->andReturn($payload + ['surveyName' => 'Renamed']);
    $endpoint->shouldReceive('destroy')->with('survey-1')->once();

    $resource = (new SurveyResource($endpoint))->setSurveyId('survey-1');

    expect($resource->getSurvey())->toBeInstanceOf(SurveyModel::class)
        ->and($resource->updateSurvey(new SurveyUpdateModel('Renamed', null, null, null))->surveyName)->toBe('Demo');

    $resource->deleteSurvey();
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

    $counts = (new SurveyResource($endpoint))->setSurveyId('survey-1')->getSurveyCounts();

    expect($counts)->toBeInstanceOf(SurveyCountsModel::class)
        ->and($counts->successfulCount)->toBe(10)
        ->and($counts->activeLiveCount)->toBe(2);
});

it('returns the custom columns as a collection of names', function () {
    // `customColumns` returns an array of strings — the column names are
    // values, not keys, which is why they survive the key normalizer.
    $endpoint = Mockery::mock(SurveyEndpointInterface::class);

    $endpoint->shouldReceive('getCustomColumns')->with('survey-1')->once()->andReturn(['Phone', 'Email']);

    $columns = (new SurveyResource($endpoint))->setSurveyId('survey-1')->getCustomColumns();

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
        ->withArgs(fn (string $s, string $sp, array $body) => $body['name'] === 'Renamed')
        ->once()
        ->andReturn($payload);
    $endpoint->shouldReceive('activate')
        ->with('survey-1', 'sp-1', ['target' => 5])
        ->once()
        ->andReturn(['isActivated' => true]);
    $endpoint->shouldReceive('replace')
        ->with('survey-1', 'sp-1', ['spareSamplingPointId' => 'sp-9', 'target' => null])
        ->once()
        ->andReturn(['success' => true]);

    $resource = (new SamplingPointResource($endpoint))
        ->setSurveyId('survey-1')
        ->setSamplingPointId('sp-1');

    expect($resource->getSamplingPoint())->toBeInstanceOf(SamplingPointResponseModel::class)
        ->and($resource->updateSamplingPoint(new SamplingPointUpdateRequestModel(name: 'Renamed'))->name)->toBe('SP 1')
        ->and($resource->activateSamplingPoint(['target' => 5])->isActivated)->toBeTrue()
        ->and($resource->replaceSamplingPoint(new ReplaceSamplingPointWithSpareRequestModel('sp-9'))->success)->toBeTrue();

    $resource->deleteSamplingPoint();
});

it('hands both scopes to every service it resolves', function () {
    $resource = (new SamplingPointResource(Mockery::mock(SamplingPointEndpointInterface::class)))
        ->setSurveyId('survey-1')
        ->setSamplingPointId('sp-1');

    foreach ([
        'addresses' => SamplingPointAddressService::class,
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

// ── SamplingPointAddressResource ─────────────────────────────────────────

it('reads the address it is scoped to', function () {
    $endpoint = Mockery::mock(SamplingPointAddressEndpointInterface::class);

    $endpoint->shouldReceive('get')
        ->with('survey-1', 'sp-1', 'addr-1')
        ->once()
        ->andReturn(['addressId' => 'addr-1', 'details' => '1 Example Street', 'appointmentDate' => null, 'sampleData' => null]);

    $address = (new SamplingPointAddressResource($endpoint))
        ->setSurveyId('survey-1')
        ->setSamplingPointId('sp-1')
        ->setAddressId('addr-1')
        ->getAddress();

    expect($address)->toBeInstanceOf(AddressModel::class)
        ->and($address->details)->toBe('1 Example Street');
});

it('deletes the address it is scoped to', function () {
    $endpoint = Mockery::mock(SamplingPointAddressEndpointInterface::class);

    $endpoint->shouldReceive('delete')->with('survey-1', 'sp-1', 'addr-1')->once();

    (new SamplingPointAddressResource($endpoint))
        ->setSurveyId('survey-1')
        ->setSamplingPointId('sp-1')
        ->setAddressId('addr-1')
        ->deleteAddress();
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
        ->getSampleRecord();

    expect($record)->toBeInstanceOf(Collection::class)
        ->and($record->all())->toBe(['InterviewId' => '7', 'Name' => 'Ada']);
});

it('returns null when the sample record is empty', function () {
    $item = Mockery::mock(SurveySampleEndpointInterface::class);

    $item->shouldReceive('get')->with('survey-1', 7)->once()->andReturn("InterviewId\tName\n");

    $record = (new SurveySampleResource($item, Mockery::mock(SurveySampleCollectionEndpointInterface::class)))
        ->setSurveyId('survey-1')
        ->setInterviewId(7)
        ->getSampleRecord();

    expect($record)->toBeNull();
});

it('deletes and updates sample records through the collection endpoint', function () {
    $collection = Mockery::mock(SurveySampleCollectionEndpointInterface::class);

    $collection->shouldReceive('destroy')
        ->with('survey-1', [['name' => 'Status', 'op' => 'eq', 'value' => 'Open']])
        ->once()
        ->andReturn(['activityId' => 'delete-1']);

    $collection->shouldReceive('update')
        ->withArgs(fn (string $surveyId, array $payload) => $payload['sampleRecordId'] === 7)
        ->once()
        ->andReturn(['resultStatus' => true]);

    $resource = (new SurveySampleResource(Mockery::mock(SurveySampleEndpointInterface::class), $collection))
        ->setSurveyId('survey-1')
        ->setInterviewId(7);

    $deleted = $resource->deleteSampleData([new SampleFilterModel('Status', 'eq', 'Open')]);

    expect($deleted)->toBeInstanceOf(BackgroundActivityStatus::class)
        ->and($deleted->activityId)->toBe('delete-1');

    $updated = $resource->updateSampleRecord(new SurveyUpdateSampleRecordModel(7));

    expect($updated)->toBeInstanceOf(SampleUpdateStatus::class)
        ->and($updated->resultStatus)->toBeTrue();
});

it('refuses a sample call before the survey scope is set', function () {
    $resource = (new SurveySampleResource(
        Mockery::mock(SurveySampleEndpointInterface::class),
        Mockery::mock(SurveySampleCollectionEndpointInterface::class),
    ))->setInterviewId(7);

    expect(fn () => $resource->getSampleRecord())->toThrow(MissingScopeException::class);
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
