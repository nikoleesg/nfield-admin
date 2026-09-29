<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGroupCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGroupDirectoryAssignmentsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGroupEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGroupLocalAssignmentsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGroupSurveysEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGroupModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGroups\SurveyGroupDirectoryAssignmentModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGroups\SurveyGroupLocalAssignmentModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Enums\DirectoryObjectTypeEnum;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;
use Nikoleesg\NfieldAdmin\Facades\NfieldManager;
use Nikoleesg\NfieldAdmin\Services\SurveyGroupCollectionService;
use Nikoleesg\NfieldAdmin\Services\SurveyGroupService;

afterEach(function () {
    Mockery::close();
});

function surveyGroupServiceWith(array $mocks = []): SurveyGroupService
{
    return (new SurveyGroupService(
        $mocks['group'] ?? Mockery::mock(SurveyGroupEndpointInterface::class),
        $mocks['directory'] ?? Mockery::mock(SurveyGroupDirectoryAssignmentsEndpointInterface::class),
        $mocks['local'] ?? Mockery::mock(SurveyGroupLocalAssignmentsEndpointInterface::class),
        $mocks['surveys'] ?? Mockery::mock(SurveyGroupSurveysEndpointInterface::class),
    ))->setSurveyGroupId(7);
}

it('lists the survey groups as models', function () {
    $endpoint = Mockery::mock(SurveyGroupCollectionEndpointInterface::class);

    $endpoint->shouldReceive('list')->once()->andReturn([
        ['surveyGroupId' => 7, 'name' => 'Retail', 'description' => null, 'creationDate' => '2026-01-02T03:04:05Z'],
    ]);

    $groups = (new SurveyGroupCollectionService($endpoint))->list();

    expect($groups)->toBeInstanceOf(Collection::class)
        ->and($groups->first())->toBeInstanceOf(SurveyGroupModel::class)
        ->and($groups->first()->surveyGroupId)->toBe(7)
        ->and($groups->first()->creationDate)->toBeInstanceOf(Carbon::class);
});

it('reads the group it is scoped to', function () {
    $group = Mockery::mock(SurveyGroupEndpointInterface::class);
    $group->shouldReceive('get')->with(7)->once()->andReturn(['surveyGroupId' => 7, 'name' => 'Retail']);

    expect(surveyGroupServiceWith(['group' => $group])->get()->name)->toBe('Retail');
});

it('lists the directory and local assignments of the group', function () {
    $directory = Mockery::mock(SurveyGroupDirectoryAssignmentsEndpointInterface::class);
    $local = Mockery::mock(SurveyGroupLocalAssignmentsEndpointInterface::class);

    $directory->shouldReceive('list')->with(7, ['$filter' => 'ObjectType eq 3'])->once()->andReturn([[
        'surveyGroupId' => 7,
        'tenantId' => '5f2c1f0e-0000-4000-8000-000000000001',
        'objectId' => '5f2c1f0e-0000-4000-8000-000000000002',
        'objectType' => 3,
        'dateAdded' => '2026-09-01T00:00:00Z',
    ]]);
    $local->shouldReceive('list')->with(7)->once()->andReturn([
        ['surveyGroupId' => 7, 'nativeIdentityId' => 'user-1', 'dateAdded' => '2026-09-01T00:00:00Z'],
    ]);

    $service = surveyGroupServiceWith(['directory' => $directory, 'local' => $local]);

    $directoryAssignment = $service->directoryAssignments(['$filter' => 'ObjectType eq 3'])->first();
    $localAssignment = $service->localAssignments()->first();

    expect($directoryAssignment)->toBeInstanceOf(SurveyGroupDirectoryAssignmentModel::class)
        ->and($directoryAssignment->objectType)->toBe(DirectoryObjectTypeEnum::SecurityGroup)
        ->and($directoryAssignment->objectId)->toBe('5f2c1f0e-0000-4000-8000-000000000002')
        ->and($localAssignment)->toBeInstanceOf(SurveyGroupLocalAssignmentModel::class)
        ->and($localAssignment->nativeIdentityId)->toBe('user-1');
});

it('lists the surveys in the group as survey models', function () {
    $surveys = Mockery::mock(SurveyGroupSurveysEndpointInterface::class);
    $surveys->shouldReceive('list')->with(7, [])->once()->andReturn([surveyPayload()]);

    expect(surveyGroupServiceWith(['surveys' => $surveys])->surveys()->first())->toBeInstanceOf(SurveyModel::class);
});

it('scopes the service it hands out to one group', function () {
    $group = NfieldManager::surveyGroups()->forSurveyGroup(7);

    expect($group)->toBeInstanceOf(SurveyGroupService::class)
        ->and($group->getSurveyGroupId())->toBe(7);
});

it('refuses a group call before the group is set', function () {
    $service = new SurveyGroupService(
        Mockery::mock(SurveyGroupEndpointInterface::class),
        Mockery::mock(SurveyGroupDirectoryAssignmentsEndpointInterface::class),
        Mockery::mock(SurveyGroupLocalAssignmentsEndpointInterface::class),
        Mockery::mock(SurveyGroupSurveysEndpointInterface::class),
    );

    expect(fn () => $service->get())->toThrow(MissingScopeException::class);
});
