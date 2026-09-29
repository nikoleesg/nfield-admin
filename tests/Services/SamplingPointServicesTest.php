<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAssignmentEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointQuotaTargetsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\Addresses\AddressModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\InterviewerSamplingPointAssignmentModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointCreateRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointQuotaLevelTargetUpdateRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointQuotaTargetModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointResponseModel;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;
use Nikoleesg\NfieldAdmin\Resources\SamplingPointResource;
use Nikoleesg\NfieldAdmin\Services\SamplingPointAddressCollectionService;
use Nikoleesg\NfieldAdmin\Services\SamplingPointAddressService;
use Nikoleesg\NfieldAdmin\Services\SamplingPointAssignmentService;
use Nikoleesg\NfieldAdmin\Services\SamplingPointCollectionService;
use Nikoleesg\NfieldAdmin\Services\SamplingPointQuotaTargetsService;

afterEach(function () {
    Mockery::close();
});

// ── Sampling points ──────────────────────────────────────────────────────

it('lists and filters sampling points as models', function () {
    $collection = Mockery::mock(SamplingPointCollectionEndpointInterface::class);

    $collection->shouldReceive('find')
        ->with('survey-1', [])
        ->once()
        ->andReturn([samplingPointPayload()]);

    $collection->shouldReceive('find')
        ->with('survey-1', ['kind' => 1])
        ->once()
        ->andReturn([samplingPointPayload(['samplingPointId' => 'sp-2'])]);

    $service = (new SamplingPointCollectionService(
        $collection,
        Mockery::mock(SurveyEndpointInterface::class),
    ))->setSurveyId('survey-1');

    $all = $service->list();

    expect($all)->toBeInstanceOf(Collection::class)
        ->and($all->first())->toBeInstanceOf(SamplingPointResponseModel::class)
        ->and($all->first()->customDataItems[0]->name)->toBe('region')
        ->and($service->find(['kind' => 1])->first()->samplingPointId)->toBe('sp-2');
});

it('creates a sampling point from an array or a model', function () {
    $collection = Mockery::mock(SamplingPointCollectionEndpointInterface::class);

    $collection->shouldReceive('create')
        ->withArgs(fn (string $surveyId, array $payload) => $surveyId === 'survey-1' && $payload['name'] === 'SP 1')
        ->twice()
        ->andReturn(samplingPointPayload());

    $service = (new SamplingPointCollectionService(
        $collection,
        Mockery::mock(SurveyEndpointInterface::class),
    ))->setSurveyId('survey-1');

    expect($service->create(['name' => 'SP 1'])->samplingPointId)->toBe('sp-1')
        ->and($service->create(new SamplingPointCreateRequestModel(name: 'SP 1'))->name)->toBe('SP 1');
});

it('wraps the ids when batch-activating spare sampling points', function () {
    // The wrapper lives in the request model, not in the endpoint: the
    // endpoint takes the body it is given.
    $surveyEndpoint = Mockery::mock(SurveyEndpointInterface::class);

    $surveyEndpoint->shouldReceive('batchActivateSamplingPoints')
        ->with('survey-1', ['samplingPointIds' => ['sp-1', 'sp-2']])
        ->once()
        ->andReturn(['isActivated' => true]);

    $service = (new SamplingPointCollectionService(
        Mockery::mock(SamplingPointCollectionEndpointInterface::class),
        $surveyEndpoint,
    ))->setSurveyId('survey-1');

    expect($service->activate(['sp-1', 'sp-2'])->isActivated)->toBeTrue();
});

it('hands its scope to the sampling point resource it returns', function () {
    $service = (new SamplingPointCollectionService(
        Mockery::mock(SamplingPointCollectionEndpointInterface::class),
        Mockery::mock(SurveyEndpointInterface::class),
    ))->setSurveyId('survey-1');

    $resource = $service->forSamplingPoint('sp-1');

    expect($resource)->toBeInstanceOf(SamplingPointResource::class)
        ->and($resource->getSurveyId())->toBe('survey-1')
        ->and($resource->getSamplingPointId())->toBe('sp-1');
});

it('refuses a sampling point call before the survey scope is set', function () {
    $service = new SamplingPointCollectionService(
        Mockery::mock(SamplingPointCollectionEndpointInterface::class),
        Mockery::mock(SurveyEndpointInterface::class),
    );

    expect(fn () => $service->list())->toThrow(MissingScopeException::class);
});

// ── Addresses ────────────────────────────────────────────────────────────

it('lists, filters and creates sampling point addresses', function () {
    $collection = Mockery::mock(SamplingPointAddressCollectionEndpointInterface::class);

    $address = [
        'addressId' => 'addr-1',
        'details' => '1 Example Street',
        'appointmentDate' => '2026-09-23T10:00:00Z',
        'sampleData' => [['name' => 'phone', 'value' => '555']],
    ];

    $collection->shouldReceive('find')->with('survey-1', 'sp-1', [])->once()->andReturn([$address]);
    $collection->shouldReceive('find')->with('survey-1', 'sp-1', ['status' => 1])->once()->andReturn([]);
    $collection->shouldReceive('create')
        ->withArgs(fn (string $s, string $sp, array $payload) => $payload['details'] === '2 Example Street')
        ->once()
        ->andReturn(['addressId' => 'addr-2', 'details' => '2 Example Street', 'appointmentDate' => null, 'sampleData' => null]);

    $service = (new SamplingPointAddressCollectionService($collection))
        ->setSurveyId('survey-1')
        ->setSamplingPointId('sp-1');

    $list = $service->list();

    expect($list->first())->toBeInstanceOf(AddressModel::class)
        ->and($list->first()->addressId)->toBe('addr-1')
        ->and($list->first()->appointmentDate->format('Y-m-d'))->toBe('2026-09-23')
        ->and($service->find(['status' => 1]))->toHaveCount(0)
        ->and($service->create(['addressId' => null, 'details' => '2 Example Street', 'appointmentDate' => null, 'sampleData' => null])->addressId)
        ->toBe('addr-2');
});

it('hands all three scopes to the address service it returns', function () {
    $service = (new SamplingPointAddressCollectionService(
        Mockery::mock(SamplingPointAddressCollectionEndpointInterface::class),
    ))->setSurveyId('survey-1')->setSamplingPointId('sp-1');

    $address = $service->forAddress('addr-1');

    expect($address)->toBeInstanceOf(SamplingPointAddressService::class)
        ->and($address->getSurveyId())->toBe('survey-1')
        ->and($address->getSamplingPointId())->toBe('sp-1')
        ->and($address->getAddressId())->toBe('addr-1');
});

it('refuses an address call before the sampling point scope is set', function () {
    $service = (new SamplingPointAddressCollectionService(
        Mockery::mock(SamplingPointAddressCollectionEndpointInterface::class),
    ))->setSurveyId('survey-1');

    expect(fn () => $service->list())->toThrow(MissingScopeException::class);
});

// ── Assignments ──────────────────────────────────────────────────────────

it('lists, assigns and unassigns interviewers on a sampling point', function () {
    $endpoint = Mockery::mock(SamplingPointAssignmentEndpointInterface::class);

    $endpoint->shouldReceive('list')->with('survey-1', 'sp-1')->once()->andReturn([
        ['interviewerId' => 'ivw-1', 'userName' => 'ada', 'firstName' => 'Ada', 'lastName' => 'L', 'assigned' => true, 'active' => true],
    ]);

    $endpoint->shouldReceive('assign')
        ->with('survey-1', 'sp-1', 'ivw-1')
        ->once()
        ->andReturn(['samplingPointIds' => ['sp-1'], 'interviewerIds' => ['ivw-1']]);

    $endpoint->shouldReceive('unassign')->with('survey-1', 'sp-1', 'ivw-1')->once();

    $service = (new SamplingPointAssignmentService($endpoint))
        ->setSurveyId('survey-1')
        ->setSamplingPointId('sp-1');

    $list = $service->list();

    expect($list->first())->toBeInstanceOf(InterviewerSamplingPointAssignmentModel::class)
        ->and($list->first()->assigned)->toBeTrue()
        ->and($service->assign('ivw-1')->interviewerIds)->toBe(['ivw-1']);

    $service->unassign('ivw-1');
});

// ── Quota targets ────────────────────────────────────────────────────────

it('lists, reads and sets sampling point quota targets', function () {
    $endpoint = Mockery::mock(SamplingPointQuotaTargetsEndpointInterface::class);

    $target = [
        'levelId' => 'level-1',
        'target' => 10,
        'successfulCount' => 1,
        'unsuccessfulCount' => 0,
        'droppedOutCount' => 0,
        'rejectedCount' => 0,
    ];

    $endpoint->shouldReceive('list')->with('survey-1', 'sp-1')->once()->andReturn([$target]);
    $endpoint->shouldReceive('get')->with('survey-1', 'sp-1', 'level-1')->once()->andReturn($target);
    $endpoint->shouldReceive('update')
        ->with('survey-1', 'sp-1', 'level-1', ['target' => 20])
        ->once()
        ->andReturn(['levelId' => 'level-1', 'target' => 20]);

    $service = (new SamplingPointQuotaTargetsService($endpoint))
        ->setSurveyId('survey-1')
        ->setSamplingPointId('sp-1');

    expect($service->list()->first())->toBeInstanceOf(SamplingPointQuotaTargetModel::class)
        ->and($service->get('level-1')->target)->toBe(10)
        ->and($service->update('level-1', new SamplingPointQuotaLevelTargetUpdateRequestModel(target: 20))->target)->toBe(20);
});

// ── SamplingPointAddressService ──────────────────────────────────────────

it('reads the address it is scoped to', function () {
    $endpoint = Mockery::mock(SamplingPointAddressEndpointInterface::class);

    $endpoint->shouldReceive('get')
        ->with('survey-1', 'sp-1', 'addr-1')
        ->once()
        ->andReturn(['addressId' => 'addr-1', 'details' => '1 Example Street', 'appointmentDate' => null, 'sampleData' => null]);

    $address = (new SamplingPointAddressService($endpoint))
        ->setSurveyId('survey-1')
        ->setSamplingPointId('sp-1')
        ->setAddressId('addr-1')
        ->get();

    expect($address)->toBeInstanceOf(AddressModel::class)
        ->and($address->details)->toBe('1 Example Street');
});

it('deletes the address it is scoped to', function () {
    $endpoint = Mockery::mock(SamplingPointAddressEndpointInterface::class);

    $endpoint->shouldReceive('delete')->with('survey-1', 'sp-1', 'addr-1')->once();

    (new SamplingPointAddressService($endpoint))
        ->setSurveyId('survey-1')
        ->setSamplingPointId('sp-1')
        ->setAddressId('addr-1')
        ->delete();
});
