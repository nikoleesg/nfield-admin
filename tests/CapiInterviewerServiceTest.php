<?php

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerAssignmentData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerResponseData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\EditCapiInterviewerRequestData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\NewCapiInterviewerRequestData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\ResetCapiInterviewerPasswordRequestData;
use Nikoleesg\NfieldAdmin\Resources\CapiInterviewerResource;
use Nikoleesg\NfieldAdmin\Services\CapiInterviewerService;

afterEach(function () {
    \Mockery::close();
});

function capiInterviewerPayload(array $overrides = []): array
{
    return array_merge([
        'interviewerId' => 'int-1',
        'userName' => 'user-1',
        'firstName' => 'John',
        'lastName' => 'Doe',
        'emailAddress' => null,
        'telephoneNumber' => null,
        'lastPasswordChangeTime' => '2024-01-02T03:04:05Z',
        'clientInterviewerId' => 'C0000001',
        'successfulCount' => 1,
        'unsuccessfulCount' => 2,
        'droppedOutCount' => 0,
        'rejectedCount' => 0,
        'lastSyncDate' => null,
        'isFullSynced' => true,
        'isLastSyncSuccessful' => false,
        'isSupervisor' => true,
    ], $overrides);
}

function capiInterviewerResponsePayload(array $overrides = []): array
{
    return array_merge([
        'firstName' => 'John',
        'lastName' => 'Doe',
        'emailAddress' => null,
        'telephoneNumber' => null,
        'isSupervisor' => false,
        'interviewerId' => 'int-1',
        'userName' => 'user-1',
        'clientInterviewerId' => 'C0000001',
    ], $overrides);
}

function capiAssignmentPayload(array $overrides = []): array
{
    return array_merge([
        'surveyName' => 'Survey A',
        'surveyId' => 'survey-1',
        'interviewer' => 'John Doe',
        'interviewerId' => 'int-1',
        'discriminator' => 'Survey',
        'assigned' => true,
        'active' => true,
        'isGroupAssignment' => false,
        'assignedTarget' => 10,
        'assignedSamplingPointTarget' => null,
        'successful' => 1,
        'screenedOut' => 0,
        'droppedOut' => 0,
        'rejected' => 0,
        'lastSyncDate' => '2024-01-02T03:04:05Z',
        'isFullSynced' => true,
        'isLastSyncSuccessful' => true,
    ], $overrides);
}

it('lists capi interviewers as DTO collection', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);

    $collectionEndpoint
        ->shouldReceive('list')
        ->once()
        ->andReturn([
            capiInterviewerPayload(),
            capiInterviewerPayload(['interviewerId' => 'int-2', 'userName' => 'user-2']),
        ]);

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint);

    $result = $service->listCapiInterviewers();

    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->first())->toBeInstanceOf(CapiInterviewerData::class);
    expect($result->first()->interviewerId)->toBe('int-1');
    expect($result->first()->lastPasswordChangeTime)->toBeInstanceOf(Carbon::class);
});

it('finds capi interviewers as DTO collection', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);

    $filter = ['$top' => 1];

    $collectionEndpoint
        ->shouldReceive('find')
        ->with($filter)
        ->once()
        ->andReturn([capiInterviewerPayload(['interviewerId' => 'int-99'])]);

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint);

    $result = $service->findCapiInterviewers($filter);

    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->first())->toBeInstanceOf(CapiInterviewerData::class);
    expect($result->first()->interviewerId)->toBe('int-99');
});

it('creates a capi interviewer from DTO and returns response DTO', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);

    $dto = new NewCapiInterviewerRequestData(
        firstName: 'John',
        lastName: 'Doe',
        emailAddress: null,
        telephoneNumber: null,
        isSupervisor: true,
        userName: 'user-1',
        password: 'secret',
        clientInterviewerId: 'C0000001',
    );

    $collectionEndpoint
        ->shouldReceive('create')
        ->with(Mockery::type(NewCapiInterviewerRequestData::class))
        ->once()
        ->andReturn(capiInterviewerResponsePayload(['isSupervisor' => true]));

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint);

    $result = $service->createCapiInterviewer($dto);

    expect($result)->toBeInstanceOf(CapiInterviewerResponseData::class);
    expect($result->isSupervisor)->toBeTrue();
    expect($result->userName)->toBe('user-1');
});

it('gets an interviewer by client id and returns DTO', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);

    $collectionEndpoint
        ->shouldReceive('getByClientId')
        ->with('C0000001')
        ->once()
        ->andReturn(capiInterviewerPayload(['clientInterviewerId' => 'C0000001']));

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint);

    $result = $service->getByClientId('C0000001');

    expect($result)->toBeInstanceOf(CapiInterviewerData::class);
    expect($result->clientInterviewerId)->toBe('C0000001');
});

it('gets an interviewer by id and returns DTO', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);

    $endpoint
        ->shouldReceive('get')
        ->with('int-1')
        ->once()
        ->andReturn(capiInterviewerPayload(['interviewerId' => 'int-1']));

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint);

    $result = $service->getCapiInterviewer('int-1');

    expect($result)->toBeInstanceOf(CapiInterviewerData::class);
    expect($result->interviewerId)->toBe('int-1');
});

it('updates an interviewer from DTO and returns response DTO', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);

    $dto = new EditCapiInterviewerRequestData(firstName: 'Jane');

    $endpoint
        ->shouldReceive('update')
        ->with('int-1', Mockery::type(EditCapiInterviewerRequestData::class))
        ->once()
        ->andReturn(capiInterviewerResponsePayload(['firstName' => 'Jane']));

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint);

    $result = $service->updateCapiInterviewer('int-1', $dto);

    expect($result)->toBeInstanceOf(CapiInterviewerResponseData::class);
    expect($result->firstName)->toBe('Jane');
});

it('resets password and returns response DTO', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);

    $dto = new ResetCapiInterviewerPasswordRequestData('new-password');

    $endpoint
        ->shouldReceive('resetPassword')
        ->with('int-1', Mockery::type(ResetCapiInterviewerPasswordRequestData::class))
        ->once()
        ->andReturn(capiInterviewerResponsePayload());

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint);

    $result = $service->resetPassword('int-1', $dto);

    expect($result)->toBeInstanceOf(CapiInterviewerResponseData::class);
});

it('deletes an interviewer', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);

    $endpoint
        ->shouldReceive('delete')
        ->with('int-1')
        ->once()
        ->andReturn(true);

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint);

    expect($service->deleteCapiInterviewer('int-1'))->toBeTrue();
});

it('gets assignments as DTO collection', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);

    $endpoint
        ->shouldReceive('getAssignments')
        ->with('int-1')
        ->once()
        ->andReturn([
            capiAssignmentPayload(),
            capiAssignmentPayload(['surveyId' => 'survey-2', 'successful' => 3]),
        ]);

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint);

    $result = $service->getAssignments('int-1');

    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->first())->toBeInstanceOf(CapiInterviewerAssignmentData::class);
    expect($result->first()->lastSyncDate)->toBeInstanceOf(Carbon::class);
});

it('gets offices as collection of strings', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);

    $endpoint
        ->shouldReceive('getOffices')
        ->with('int-1')
        ->once()
        ->andReturn(['office-1', 'office-2']);

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint);

    $result = $service->getOffices('int-1');

    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->all())->toBe(['office-1', 'office-2']);
});

it('adds and removes office assignments', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);

    $endpoint
        ->shouldReceive('updateOffice')
        ->with('int-1', 'office-1')
        ->once()
        ->andReturn(true);

    $endpoint
        ->shouldReceive('deleteOffice')
        ->with('int-1', 'office-1')
        ->once()
        ->andReturn(true);

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint);

    expect($service->updateOffice('int-1', 'office-1'))->toBeTrue();
    expect($service->deleteOffice('int-1', 'office-1'))->toBeTrue();
});

it('provides a fluent resource that proxies to endpoint', function () {
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);

    $endpoint
        ->shouldReceive('get')
        ->with('int-1')
        ->once()
        ->andReturn(capiInterviewerPayload(['interviewerId' => 'int-1']));

    $endpoint
        ->shouldReceive('update')
        ->with('int-1', Mockery::type(EditCapiInterviewerRequestData::class))
        ->once()
        ->andReturn(capiInterviewerResponsePayload(['firstName' => 'Jane']));

    $endpoint
        ->shouldReceive('resetPassword')
        ->with('int-1', Mockery::type(ResetCapiInterviewerPasswordRequestData::class))
        ->once()
        ->andReturn(capiInterviewerResponsePayload());

    $endpoint
        ->shouldReceive('delete')
        ->with('int-1')
        ->once()
        ->andReturn(true);

    $endpoint
        ->shouldReceive('getAssignments')
        ->with('int-1')
        ->once()
        ->andReturn([capiAssignmentPayload()]);

    $endpoint
        ->shouldReceive('getOffices')
        ->with('int-1')
        ->once()
        ->andReturn(['office-1']);

    $endpoint
        ->shouldReceive('updateOffice')
        ->with('int-1', 'office-1')
        ->once()
        ->andReturn(true);

    $endpoint
        ->shouldReceive('deleteOffice')
        ->with('int-1', 'office-1')
        ->once()
        ->andReturn(true);

    $resource = (new CapiInterviewerResource($endpoint))->setInterviewerId('int-1');

    expect($resource->get())->toBeInstanceOf(CapiInterviewerData::class);
    expect($resource->update(new EditCapiInterviewerRequestData(firstName: 'Jane')))->toBeInstanceOf(CapiInterviewerResponseData::class);
    expect($resource->resetPassword(new ResetCapiInterviewerPasswordRequestData('pw')))->toBeInstanceOf(CapiInterviewerResponseData::class);
    expect($resource->delete())->toBeTrue();
    expect($resource->getAssignments()->first())->toBeInstanceOf(CapiInterviewerAssignmentData::class);
    expect($resource->getOffices()->all())->toBe(['office-1']);
    expect($resource->updateOffice('office-1'))->toBeTrue();
    expect($resource->deleteOffice('office-1'))->toBeTrue();
});
