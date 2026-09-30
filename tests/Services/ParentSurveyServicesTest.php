<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ParentSurveyCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ParentSurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ParentSurveyWaveCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ParentSurveyWaveEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyWaveEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\ParentSurvey\ParentSurveyCreateRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;
use Nikoleesg\NfieldAdmin\Facades\NfieldManager;
use Nikoleesg\NfieldAdmin\Resources\ParentSurveyResource;
use Nikoleesg\NfieldAdmin\Services\ParentSurveyCollectionService;
use Nikoleesg\NfieldAdmin\Services\ParentSurveyWaveCollectionService;
use Nikoleesg\NfieldAdmin\Services\ParentSurveyWaveService;

afterEach(function () {
    Mockery::close();
});

it('lists, filters and creates parent surveys', function () {
    $endpoint = Mockery::mock(ParentSurveyCollectionEndpointInterface::class);

    $endpoint->shouldReceive('list')->withNoArgs()->once()->andReturn([surveyPayload()]);
    $endpoint->shouldReceive('list')->with(['$top' => 1])->once()->andReturn([surveyPayload()]);
    // surveyGroupId is Optional: left out unless set.
    $endpoint->shouldReceive('create')
        ->with(['surveyName' => 'Tracker', 'clientName' => null, 'description' => null])
        ->once()
        ->andReturn(surveyPayload(['surveyName' => 'Tracker']));
    $endpoint->shouldReceive('create')
        ->with(['surveyName' => 'Tracker', 'clientName' => null, 'description' => null, 'surveyGroupId' => 7])
        ->once()
        ->andReturn(surveyPayload(['surveyName' => 'Tracker']));

    $service = new ParentSurveyCollectionService($endpoint);

    $all = $service->list();

    expect($all)->toBeInstanceOf(Collection::class)
        ->and($all->first())->toBeInstanceOf(SurveyModel::class)
        ->and($service->find(['$top' => 1]))->toHaveCount(1)
        ->and($service->create(['surveyName' => 'Tracker'])->surveyName)->toBe('Tracker')
        ->and($service->create(new ParentSurveyCreateRequestModel('Tracker', surveyGroupId: 7))->surveyName)->toBe('Tracker');
});

it('reads and sets the parent survey auto-start check through the resource', function () {
    $endpoint = Mockery::mock(ParentSurveyEndpointInterface::class);

    $endpoint->shouldReceive('getCheckMinSuccessfulsBeforeAutoStart')->with('parent-1')->once()
        ->andReturn(['checkMinSuccessfulsBeforeAutoStart' => false]);
    $endpoint->shouldReceive('updateCheckMinSuccessfulsBeforeAutoStart')
        ->with('parent-1', ['checkMinSuccessfulsBeforeAutoStart' => true])
        ->once();

    app()->instance(ParentSurveyEndpointInterface::class, $endpoint);

    $parent = NfieldManager::parentSurveys()->forParentSurvey('parent-1');

    expect($parent)->toBeInstanceOf(ParentSurveyResource::class)
        ->and($parent->checkMinSuccessfulsBeforeAutoStart()->checkMinSuccessfulsBeforeAutoStart)->toBeFalse();

    $parent->updateCheckMinSuccessfulsBeforeAutoStart(true);
});

it('lists and creates the waves of a parent survey', function () {
    $endpoint = Mockery::mock(ParentSurveyWaveCollectionEndpointInterface::class);

    $endpoint->shouldReceive('list')->with('parent-1')->once()->andReturn([surveyPayload(['surveyName' => 'Wave 1'])]);
    $endpoint->shouldReceive('create')
        ->with('parent-1', ['surveyName' => 'Wave 2', 'clientName' => null, 'description' => null])
        ->once()
        ->andReturn(surveyPayload(['surveyName' => 'Wave 2']));

    $waves = (new ParentSurveyWaveCollectionService($endpoint))->setParentSurveyId('parent-1');

    expect($waves->list()->first()->surveyName)->toBe('Wave 1')
        ->and($waves->create(['surveyName' => 'Wave 2'])->surveyName)->toBe('Wave 2');
});

it('copies a wave reached through its parent survey', function () {
    $copy = Mockery::mock(ParentSurveyWaveEndpointInterface::class);
    $copy->shouldReceive('copy')->with('parent-1', 'wave-1', ['surveyName' => 'Wave 2'])->once()
        ->andReturn(surveyPayload(['surveyName' => 'Wave 2']));

    app()->instance(ParentSurveyWaveEndpointInterface::class, $copy);

    $wave = NfieldManager::parentSurveys()->forParentSurvey('parent-1')->waves()->forWave('wave-1');

    expect($wave)->toBeInstanceOf(ParentSurveyWaveService::class)
        ->and($wave->getParentSurveyId())->toBe('parent-1')
        ->and($wave->getWaveId())->toBe('wave-1')
        ->and($wave->copy('Wave 2')->surveyName)->toBe('Wave 2');
});

it('manages a wave\'s auto-start threshold and dates by the wave id alone', function () {
    $endpoint = Mockery::mock(SurveyWaveEndpointInterface::class);

    $endpoint->shouldReceive('getMinSuccessfulsBeforeAutoStart')->with('wave-1')->once()->andReturn(['minSuccessfulsBeforeAutoStart' => 50]);
    $endpoint->shouldReceive('updateMinSuccessfulsBeforeAutoStart')->with('wave-1', ['minSuccessfulsBeforeAutoStart' => 75])->once();
    $endpoint->shouldReceive('deleteMinSuccessfulsBeforeAutoStart')->with('wave-1')->once();
    $endpoint->shouldReceive('getStartDate')->with('wave-1')->once()->andReturn(['startDate' => '2026-10-01T00:00:00Z']);
    $endpoint->shouldReceive('updateStartDate')->with('wave-1', ['startDate' => '2026-10-01T00:00:00+00:00'])->once();
    $endpoint->shouldReceive('getStopDate')->with('wave-1')->once()->andReturn(['stopDate' => null]);
    // null clears the date, so it is sent explicitly.
    $endpoint->shouldReceive('updateStopDate')->with('wave-1', ['stopDate' => null])->once();

    app()->instance(SurveyWaveEndpointInterface::class, $endpoint);

    $wave = NfieldManager::surveyWaves()->forWave('wave-1');

    expect($wave->minSuccessfulsBeforeAutoStart()->minSuccessfulsBeforeAutoStart)->toBe(50)
        ->and($wave->startDate()->startDate)->toBeInstanceOf(Carbon::class)
        ->and($wave->stopDate()->stopDate)->toBeNull();

    $wave->updateMinSuccessfulsBeforeAutoStart(75);
    $wave->deleteMinSuccessfulsBeforeAutoStart();
    $wave->updateStartDate('2026-10-01');
    $wave->updateStopDate(null);
});

it('refuses to copy a wave before its parent survey is set', function () {
    $service = (new ParentSurveyWaveService(Mockery::mock(ParentSurveyWaveEndpointInterface::class)))->setWaveId('wave-1');

    expect(fn () => $service->copy('Wave 2'))->toThrow(MissingScopeException::class);
});
