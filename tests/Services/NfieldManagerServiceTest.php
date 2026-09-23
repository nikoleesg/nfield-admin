<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\BackgroundActivitiesEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyBlueprintsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyBaseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyFromBlueprintModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel;
use Nikoleesg\NfieldAdmin\Enums\ActivityStatusEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyStateEnum;
use Nikoleesg\NfieldAdmin\Resources\BlueprintSurveyResource;
use Nikoleesg\NfieldAdmin\Resources\CapiInterviewerResource;
use Nikoleesg\NfieldAdmin\Resources\SamplingPointResource;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;
use Nikoleesg\NfieldAdmin\Services\BackgroundActivitiesService;
use Nikoleesg\NfieldAdmin\Services\CapiInterviewerService;
use Nikoleesg\NfieldAdmin\Services\EventSubscriptionService;
use Nikoleesg\NfieldAdmin\Services\NfieldManagerService;
use Nikoleesg\NfieldAdmin\Services\RoleService;
use Nikoleesg\NfieldAdmin\Services\SurveyService;

afterEach(function () {
    Mockery::close();
});

function managerWith(SurveyService $surveyService, ?BackgroundActivitiesService $activities = null): NfieldManagerService
{
    return new NfieldManagerService(
        $surveyService,
        app(CapiInterviewerService::class),
        $activities ?? app(BackgroundActivitiesService::class),
        Mockery::mock(RoleService::class),
        Mockery::mock(EventSubscriptionService::class)
    );
}

// ── SurveyService ────────────────────────────────────────────────────────

it('lists and filters surveys as models', function () {
    $collection = Mockery::mock(SurveyCollectionEndpointInterface::class);

    $collection->shouldReceive('list')->once()->andReturn([surveyPayload()]);
    $collection->shouldReceive('find')->with(['surveyName' => 'Demo'])->once()->andReturn([surveyPayload()]);

    $service = new SurveyService(
        $collection,
        Mockery::mock(SurveyEndpointInterface::class),
        Mockery::mock(SurveyBlueprintsEndpointInterface::class),
    );

    $all = $service->listSurveys();

    expect($all)->toBeInstanceOf(Collection::class)
        ->and($all->first())->toBeInstanceOf(SurveyModel::class)
        ->and($all->first()->surveyState)->toBe(SurveyStateEnum::Started)
        ->and($service->findSurveys(['surveyName' => 'Demo'])->first()->surveyName)->toBe('Demo');
});

it('creates a survey from a blueprint', function () {
    $collection = Mockery::mock(SurveyCollectionEndpointInterface::class);

    $collection->shouldReceive('createFromBlueprint')
        ->with(['surveyName' => 'Wave 2', 'blueprintSurveyId' => 'bp-1', 'enableRespondentsGateway' => false])
        ->once()
        ->andReturn(surveyPayload(['surveyName' => 'Wave 2']));

    $service = new SurveyService(
        $collection,
        Mockery::mock(SurveyEndpointInterface::class),
        Mockery::mock(SurveyBlueprintsEndpointInterface::class),
    );

    $survey = $service->createSurveyFromBlueprint(new SurveyFromBlueprintModel('Wave 2', 'bp-1'));

    expect($survey->surveyName)->toBe('Wave 2');
});

it('searches surveys by respondent and returns the slim model', function () {
    $collection = Mockery::mock(SurveyCollectionEndpointInterface::class);

    $collection->shouldReceive('search')
        ->with('ada@example.test')
        ->once()
        ->andReturn([['surveyId' => 'survey-1', 'surveyName' => 'Demo']]);

    $service = new SurveyService(
        $collection,
        Mockery::mock(SurveyEndpointInterface::class),
        Mockery::mock(SurveyBlueprintsEndpointInterface::class),
    );

    $found = $service->findSurveysByRespondent('ada@example.test');

    expect($found->first())->toBeInstanceOf(SurveyBaseModel::class)
        ->and($found->first()->surveyId)->toBe('survey-1');
});

it('returns scoped resources for a survey and a blueprint', function () {
    $service = new SurveyService(
        Mockery::mock(SurveyCollectionEndpointInterface::class),
        Mockery::mock(SurveyEndpointInterface::class),
        Mockery::mock(SurveyBlueprintsEndpointInterface::class),
    );

    expect($service->forSurvey('survey-1'))->toBeInstanceOf(SurveyResource::class)
        ->and($service->forSurvey('survey-1')->getSurveyId())->toBe('survey-1')
        ->and($service->forBlueprintSurvey('bp-1'))->toBeInstanceOf(BlueprintSurveyResource::class);
});

// ── BackgroundActivitiesService ──────────────────────────────────────────

it('hydrates a background activity, dates and status included', function () {
    $endpoint = Mockery::mock(BackgroundActivitiesEndpointInterface::class);

    $endpoint->shouldReceive('get')->with('activity-1')->once()->andReturn([
        'id' => 'activity-1',
        'activityType' => 1,
        'activityTypeName' => 'DataDownload',
        'activityName' => 'Download data',
        'status' => ActivityStatusEnum::cases()[0]->value,
        'statusName' => 'Started',
        'userId' => 'user-1',
        'creationTime' => '2026-09-23T10:00:00.000000Z',
        'startTime' => '2026-09-23T10:00:01.000000Z',
        'finishTime' => null,
        'downloadDataUrl' => null,
    ]);

    $activity = (new BackgroundActivitiesService($endpoint))->getBackgroundActivity('activity-1');

    expect($activity)->toBeInstanceOf(BackgroundActivityResponseModel::class)
        ->and($activity->status)->toBe(ActivityStatusEnum::cases()[0])
        ->and($activity->creationTime->format('Y-m-d H:i:s'))->toBe('2026-09-23 10:00:00')
        ->and($activity->finishTime)->toBeNull();
});

// ── NfieldManagerService ─────────────────────────────────────────────────

it('delegates the survey calls it exposes', function () {
    $surveyService = Mockery::mock(SurveyService::class);

    $surveyService->shouldReceive('listSurveys')->once()->andReturn(collect(['listed']));
    $surveyService->shouldReceive('findSurveys')->with(['surveyName' => 'Demo'])->once()->andReturn(collect(['found']));
    $created = SurveyModel::from(surveyPayload(['surveyName' => 'Created']));
    $fromBlueprint = SurveyModel::from(surveyPayload(['surveyName' => 'From blueprint']));

    $surveyService->shouldReceive('createSurvey')->with(['surveyName' => 'Demo'])->once()->andReturn($created);
    $surveyService->shouldReceive('createSurveyFromBlueprint')->with(['blueprintSurveyId' => 'bp-1'])->once()->andReturn($fromBlueprint);
    $surveyService->shouldReceive('findSurveysByRespondent')->with('ada')->once()->andReturn(collect(['searched']));

    $manager = managerWith($surveyService);

    expect($manager->listSurveys()->all())->toBe(['listed'])
        ->and($manager->findSurveys(['surveyName' => 'Demo'])->all())->toBe(['found'])
        ->and($manager->createSurvey(['surveyName' => 'Demo']))->toBe($created)
        ->and($manager->createSurveyFromBlueprint(['blueprintSurveyId' => 'bp-1']))->toBe($fromBlueprint)
        ->and($manager->searchRespondent('ada')->all())->toBe(['searched']);
});

it('delegates the background activity lookup', function () {
    $activities = Mockery::mock(BackgroundActivitiesService::class);

    $activity = BackgroundActivityResponseModel::from([
        'id' => 'activity-1',
        'activityType' => 1,
        'activityTypeName' => 'DataDownload',
        'activityName' => 'Download data',
        'status' => ActivityStatusEnum::Started->value,
        'statusName' => 'Started',
        'userId' => 'user-1',
        'creationTime' => null,
        'startTime' => null,
        'finishTime' => null,
        'downloadDataUrl' => null,
    ]);

    $activities->shouldReceive('getBackgroundActivity')->with('activity-1')->once()->andReturn($activity);

    $manager = managerWith(app(SurveyService::class), $activities);

    expect($manager->getBackgroundActivity('activity-1'))->toBe($activity);
});

it('opens the fluent chain at each of its entry points', function () {
    $manager = app(NfieldManagerService::class);

    $survey = $manager->withSurvey('survey-1');
    $samplingPoint = $manager->withSurveySamplingPoint('survey-1', 'sp-1');

    expect($survey)->toBeInstanceOf(SurveyResource::class)
        ->and($survey->getSurveyId())->toBe('survey-1')
        ->and($manager->withBlueprintSurvey('bp-1'))->toBeInstanceOf(BlueprintSurveyResource::class)
        ->and($samplingPoint)->toBeInstanceOf(SamplingPointResource::class)
        ->and($samplingPoint->getSurveyId())->toBe('survey-1')
        ->and($samplingPoint->getSamplingPointId())->toBe('sp-1');
});

it('opens the CAPI interviewer chain from the service and the manager', function () {
    $manager = app(NfieldManagerService::class);

    expect($manager->withCapiInterviewer('ivw-1'))->toBeInstanceOf(CapiInterviewerResource::class)
        ->and(app(CapiInterviewerService::class)->forInterviewer('ivw-1'))->toBeInstanceOf(CapiInterviewerResource::class);
});

it('exposes the event subscriptions service', function () {
    $manager = app(NfieldManagerService::class);

    expect($manager->eventSubscriptions())->toBeInstanceOf(EventSubscriptionService::class);
});
