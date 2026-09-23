<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersAssignmentsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersOfficesEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerAssignmentModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerResponseModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\EditCapiInterviewerRequestModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\NewCapiInterviewerRequestModel;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\ResetCapiInterviewerPasswordRequestModel;
use Nikoleesg\NfieldAdmin\Resources\CapiInterviewerResource;
use Nikoleesg\NfieldAdmin\Services\CapiInterviewerService;

afterEach(function () {
    Mockery::close();
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
    $assignmentsEndpoint = Mockery::mock(CapiInterviewersAssignmentsEndpointInterface::class);
    $officesEndpoint = Mockery::mock(CapiInterviewersOfficesEndpointInterface::class);

    $collectionEndpoint
        ->shouldReceive('list')
        ->once()
        ->andReturn([
            capiInterviewerPayload(),
            capiInterviewerPayload(['interviewerId' => 'int-2', 'userName' => 'user-2']),
        ]);

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint, $assignmentsEndpoint, $officesEndpoint);

    $result = $service->list();

    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->first())->toBeInstanceOf(CapiInterviewerModel::class);
    expect($result->first()->interviewerId)->toBe('int-1');
    expect($result->first()->lastPasswordChangeTime)->toBeInstanceOf(Carbon::class);
});

it('finds capi interviewers as DTO collection', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);
    $assignmentsEndpoint = Mockery::mock(CapiInterviewersAssignmentsEndpointInterface::class);
    $officesEndpoint = Mockery::mock(CapiInterviewersOfficesEndpointInterface::class);

    $filter = ['$top' => 1];

    $collectionEndpoint
        ->shouldReceive('find')
        ->with($filter)
        ->once()
        ->andReturn([capiInterviewerPayload(['interviewerId' => 'int-99'])]);

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint, $assignmentsEndpoint, $officesEndpoint);

    $result = $service->find($filter);

    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->first())->toBeInstanceOf(CapiInterviewerModel::class);
    expect($result->first()->interviewerId)->toBe('int-99');
});

it('creates a capi interviewer from DTO and returns response DTO', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);
    $assignmentsEndpoint = Mockery::mock(CapiInterviewersAssignmentsEndpointInterface::class);
    $officesEndpoint = Mockery::mock(CapiInterviewersOfficesEndpointInterface::class);

    $dto = new NewCapiInterviewerRequestModel(
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
        ->with(Mockery::on(fn ($arg) => is_array($arg) && $arg['userName'] === 'user-1' && $arg['password'] === 'secret'))
        ->once()
        ->andReturn(capiInterviewerResponsePayload(['isSupervisor' => true]));

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint, $assignmentsEndpoint, $officesEndpoint);

    $result = $service->create($dto);

    expect($result)->toBeInstanceOf(CapiInterviewerResponseModel::class);
    expect($result->isSupervisor)->toBeTrue();
    expect($result->userName)->toBe('user-1');
});

it('gets an interviewer by client id and returns DTO', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);
    $assignmentsEndpoint = Mockery::mock(CapiInterviewersAssignmentsEndpointInterface::class);
    $officesEndpoint = Mockery::mock(CapiInterviewersOfficesEndpointInterface::class);

    $collectionEndpoint
        ->shouldReceive('getByClientId')
        ->with('C0000001')
        ->once()
        ->andReturn(capiInterviewerPayload(['clientInterviewerId' => 'C0000001']));

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint, $assignmentsEndpoint, $officesEndpoint);

    $result = $service->getByClientId('C0000001');

    expect($result)->toBeInstanceOf(CapiInterviewerModel::class);
    expect($result->clientInterviewerId)->toBe('C0000001');
});

it('gets an interviewer by id and returns DTO', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);
    $assignmentsEndpoint = Mockery::mock(CapiInterviewersAssignmentsEndpointInterface::class);
    $officesEndpoint = Mockery::mock(CapiInterviewersOfficesEndpointInterface::class);

    $endpoint
        ->shouldReceive('get')
        ->with('int-1')
        ->once()
        ->andReturn(capiInterviewerPayload(['interviewerId' => 'int-1']));

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint, $assignmentsEndpoint, $officesEndpoint);

    $result = $service->get('int-1');

    expect($result)->toBeInstanceOf(CapiInterviewerModel::class);
    expect($result->interviewerId)->toBe('int-1');
});

it('updates an interviewer from DTO and returns response DTO', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);
    $assignmentsEndpoint = Mockery::mock(CapiInterviewersAssignmentsEndpointInterface::class);
    $officesEndpoint = Mockery::mock(CapiInterviewersOfficesEndpointInterface::class);

    $dto = new EditCapiInterviewerRequestModel(firstName: 'Jane');

    $endpoint
        ->shouldReceive('update')
        ->with('int-1', Mockery::on(fn ($arg) => is_array($arg) && $arg['firstName'] === 'Jane'))
        ->once()
        ->andReturn(capiInterviewerResponsePayload(['firstName' => 'Jane']));

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint, $assignmentsEndpoint, $officesEndpoint);

    $result = $service->update('int-1', $dto);

    expect($result)->toBeInstanceOf(CapiInterviewerResponseModel::class);
    expect($result->firstName)->toBe('Jane');
});

it('resets password and returns response DTO', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);
    $assignmentsEndpoint = Mockery::mock(CapiInterviewersAssignmentsEndpointInterface::class);
    $officesEndpoint = Mockery::mock(CapiInterviewersOfficesEndpointInterface::class);

    $dto = new ResetCapiInterviewerPasswordRequestModel('new-password');

    $endpoint
        ->shouldReceive('resetPassword')
        ->with('int-1', Mockery::on(fn ($arg) => is_array($arg) && $arg['password'] === 'new-password'))
        ->once()
        ->andReturn(capiInterviewerResponsePayload());

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint, $assignmentsEndpoint, $officesEndpoint);

    $result = $service->resetPassword('int-1', $dto);

    expect($result)->toBeInstanceOf(CapiInterviewerResponseModel::class);
});

it('deletes an interviewer', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);
    $assignmentsEndpoint = Mockery::mock(CapiInterviewersAssignmentsEndpointInterface::class);
    $officesEndpoint = Mockery::mock(CapiInterviewersOfficesEndpointInterface::class);

    $endpoint
        ->shouldReceive('delete')
        ->with('int-1')
        ->once()
        ->andReturnNull();

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint, $assignmentsEndpoint, $officesEndpoint);

    $service->delete('int-1');
});

it('gets assignments as DTO collection', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);
    $assignmentsEndpoint = Mockery::mock(CapiInterviewersAssignmentsEndpointInterface::class);
    $officesEndpoint = Mockery::mock(CapiInterviewersOfficesEndpointInterface::class);

    $assignmentsEndpoint
        ->shouldReceive('list')
        ->with('int-1')
        ->once()
        ->andReturn([
            capiAssignmentPayload(),
            capiAssignmentPayload(['surveyId' => 'survey-2', 'successful' => 3]),
        ]);

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint, $assignmentsEndpoint, $officesEndpoint);

    $result = $service->getAssignments('int-1');

    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->first())->toBeInstanceOf(CapiInterviewerAssignmentModel::class);
    expect($result->first()->lastSyncDate)->toBeInstanceOf(Carbon::class);
});

it('gets offices as collection of strings', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);
    $assignmentsEndpoint = Mockery::mock(CapiInterviewersAssignmentsEndpointInterface::class);
    $officesEndpoint = Mockery::mock(CapiInterviewersOfficesEndpointInterface::class);

    $officesEndpoint
        ->shouldReceive('list')
        ->with('int-1')
        ->once()
        ->andReturn(['office-1', 'office-2']);

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint, $assignmentsEndpoint, $officesEndpoint);

    $result = $service->getOffices('int-1');

    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->all())->toBe(['office-1', 'office-2']);
});

it('adds and removes office assignments', function () {
    $collectionEndpoint = Mockery::mock(CapiInterviewersCollectionEndpointInterface::class);
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);
    $assignmentsEndpoint = Mockery::mock(CapiInterviewersAssignmentsEndpointInterface::class);
    $officesEndpoint = Mockery::mock(CapiInterviewersOfficesEndpointInterface::class);

    $officesEndpoint
        ->shouldReceive('update')
        ->with('int-1', 'office-1')
        ->once()
        ->andReturnNull();

    $officesEndpoint
        ->shouldReceive('delete')
        ->with('int-1', 'office-1')
        ->once()
        ->andReturnNull();

    $service = new CapiInterviewerService($collectionEndpoint, $endpoint, $assignmentsEndpoint, $officesEndpoint);

    $service->updateOffice('int-1', 'office-1');
    $service->deleteOffice('int-1', 'office-1');
});

it('provides a fluent resource that proxies to endpoint', function () {
    $endpoint = Mockery::mock(CapiInterviewersEndpointInterface::class);
    $assignmentsEndpoint = Mockery::mock(CapiInterviewersAssignmentsEndpointInterface::class);
    $officesEndpoint = Mockery::mock(CapiInterviewersOfficesEndpointInterface::class);

    $endpoint
        ->shouldReceive('get')
        ->with('int-1')
        ->once()
        ->andReturn(capiInterviewerPayload(['interviewerId' => 'int-1']));

    $endpoint
        ->shouldReceive('update')
        ->with('int-1', Mockery::on(fn ($arg) => is_array($arg) && $arg['firstName'] === 'Jane'))
        ->once()
        ->andReturn(capiInterviewerResponsePayload(['firstName' => 'Jane']));

    $endpoint
        ->shouldReceive('resetPassword')
        ->with('int-1', Mockery::on(fn ($arg) => is_array($arg) && $arg['password'] === 'pw'))
        ->once()
        ->andReturn(capiInterviewerResponsePayload());

    $endpoint
        ->shouldReceive('delete')
        ->with('int-1')
        ->once()
        ->andReturnNull();

    $assignmentsEndpoint
        ->shouldReceive('list')
        ->with('int-1')
        ->once()
        ->andReturn([capiAssignmentPayload()]);

    $officesEndpoint
        ->shouldReceive('list')
        ->with('int-1')
        ->once()
        ->andReturn(['office-1']);

    $officesEndpoint
        ->shouldReceive('update')
        ->with('int-1', 'office-1')
        ->once()
        ->andReturnNull();

    $officesEndpoint
        ->shouldReceive('delete')
        ->with('int-1', 'office-1')
        ->once()
        ->andReturnNull();

    $resource = (new CapiInterviewerResource($endpoint, $assignmentsEndpoint, $officesEndpoint))->setInterviewerId('int-1');

    expect($resource->get())->toBeInstanceOf(CapiInterviewerModel::class);
    expect($resource->update(new EditCapiInterviewerRequestModel(firstName: 'Jane')))->toBeInstanceOf(CapiInterviewerResponseModel::class);
    expect($resource->resetPassword(new ResetCapiInterviewerPasswordRequestModel('pw')))->toBeInstanceOf(CapiInterviewerResponseModel::class);
    $resource->delete();
    expect($resource->getAssignments()->first())->toBeInstanceOf(CapiInterviewerAssignmentModel::class);
    expect($resource->getOffices()->all())->toBe(['office-1']);
    $resource->updateOffice('office-1');
    $resource->deleteOffice('office-1');
});
