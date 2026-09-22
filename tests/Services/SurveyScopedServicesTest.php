<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyDataEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyFieldworkEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGeneralSettingsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyInterviewEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyPublicIdsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyPublishEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySamplingMethodEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySamplingPointsAssignmentsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySettingsEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingMethodModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyDataRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyFieldwork\SurveyFieldworkCountsResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGeneralSettingsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGeneralSettingsUpdateModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyPublicIdModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyPublishStateModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveySettingModel;
use Nikoleesg\NfieldAdmin\Enums\InterviewingRestrictionTypeEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyFieldworkStatusEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyPackageTypeEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyPublicIdLinkTypeEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyPublishForceUpgradeEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyPublishStateEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveySettingNameEnum;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;
use Nikoleesg\NfieldAdmin\Services\SurveyAssignmentService;
use Nikoleesg\NfieldAdmin\Services\SurveyDataService;
use Nikoleesg\NfieldAdmin\Services\SurveyFieldworkService;
use Nikoleesg\NfieldAdmin\Services\SurveyPublicIdsService;
use Nikoleesg\NfieldAdmin\Services\SurveyPublishService;
use Nikoleesg\NfieldAdmin\Services\SurveySamplingMethodService;
use Nikoleesg\NfieldAdmin\Services\SurveySettingsService;

/**
 * #29: the survey-scoped services, against mocked endpoint contracts.
 *
 * Fixtures are camelCase throughout: an endpoint sits *above* the
 * normalization boundary, so camelCase is what a real one returns. The
 * PascalCase wire format is pinned separately in `ResponseNormalizationTest`.
 */
afterEach(function () {
    Mockery::close();
});

// ── Fieldwork ────────────────────────────────────────────────────────────

it('starts and stops fieldwork', function () {
    $endpoint = Mockery::mock(SurveyFieldworkEndpointInterface::class);

    $endpoint->shouldReceive('start')->with('survey-1')->once();
    $endpoint->shouldReceive('stop')
        ->with('survey-1', ['interviewingRestrictionType' => InterviewingRestrictionTypeEnum::BlockEverything->value])
        ->once();

    $service = (new SurveyFieldworkService($endpoint))->setSurveyId('survey-1');

    $service->start();
    $service->stop();
});

it('accepts an enum, an array or a model when stopping fieldwork', function () {
    $endpoint = Mockery::mock(SurveyFieldworkEndpointInterface::class);

    $endpoint->shouldReceive('stop')
        ->with('survey-1', ['interviewingRestrictionType' => InterviewingRestrictionTypeEnum::AllowOnlyActives->value])
        ->times(2);

    $service = (new SurveyFieldworkService($endpoint))->setSurveyId('survey-1');

    $service->stop(InterviewingRestrictionTypeEnum::AllowOnlyActives);
    $service->stop(['interviewingRestrictionType' => InterviewingRestrictionTypeEnum::AllowOnlyActives->value]);
});

it('maps the fieldwork status code onto its enum', function () {
    $endpoint = Mockery::mock(SurveyFieldworkEndpointInterface::class);

    $endpoint->shouldReceive('status')->with('survey-1')->andReturn(1, 99);

    $service = (new SurveyFieldworkService($endpoint))->setSurveyId('survey-1');

    expect($service->status())->toBe(SurveyFieldworkStatusEnum::from(1))
        // An unmapped code is null rather than an exception, and the raw code
        // stays reachable through statusCode().
        ->and($service->status())->toBeNull();
});

it('returns the raw fieldwork status code', function () {
    $endpoint = Mockery::mock(SurveyFieldworkEndpointInterface::class);

    $endpoint->shouldReceive('status')->with('survey-1')->once()->andReturn(2);

    expect((new SurveyFieldworkService($endpoint))->setSurveyId('survey-1')->statusCode())->toBe(2);
});

it('hydrates the fieldwork counts, nested overview included', function () {
    $endpoint = Mockery::mock(SurveyFieldworkEndpointInterface::class);

    $endpoint->shouldReceive('counts')->with('survey-1')->once()->andReturn([
        'surveyId' => 'survey-1',
        'successful' => 10,
        'successfulLast24Hours' => 2,
        'screenedOut' => 3,
        'droppedOut' => 1,
        'rejected' => 0,
        'successfulDeleted' => 0,
        'screenedOutDeleted' => 0,
        'droppedOutDeleted' => 0,
        'rejectedDeleted' => 0,
        'activeInterviews' => 4,
        'screenedOutOverview' => [['responseCode' => 21, 'count' => 3]],
    ]);

    $counts = (new SurveyFieldworkService($endpoint))->setSurveyId('survey-1')->counts();

    expect($counts)->toBeInstanceOf(SurveyFieldworkCountsResponseModel::class)
        ->and($counts->successful)->toBe(10)
        ->and($counts->screenedOutOverview[0]->responseCode)->toBe(21);
});

it('refuses every fieldwork call before the scope is set', function () {
    $service = new SurveyFieldworkService(Mockery::mock(SurveyFieldworkEndpointInterface::class));

    expect(fn () => $service->counts())->toThrow(MissingScopeException::class)
        ->and(fn () => $service->statusCode())->toThrow(MissingScopeException::class)
        ->and(fn () => $service->stop())->toThrow(MissingScopeException::class);
});

// ── Data delivery ────────────────────────────────────────────────────────

it('requests a data download and returns the activity', function () {
    $dataEndpoint = Mockery::mock(SurveyDataEndpointInterface::class);
    $interviewEndpoint = Mockery::mock(SurveyInterviewEndpointInterface::class);

    $dataEndpoint->shouldReceive('downloadData')
        ->withArgs(function (string $surveyId, array $payload) {
            return $surveyId === 'survey-1'
                && $payload['fileName'] === 'export.zip'
                && $payload['includeSuccessful'] === true;
        })
        ->once()
        ->andReturn(['activityId' => 'activity-1']);

    $service = (new SurveyDataService($dataEndpoint, $interviewEndpoint))->setSurveyId('survey-1');

    $status = $service->downloadData(['fileName' => 'export.zip']);

    expect($status)->toBeInstanceOf(BackgroundActivityStatus::class)
        ->and($status->activityId)->toBe('activity-1');
});

it('accepts a request model as well as an array for a data download', function () {
    $dataEndpoint = Mockery::mock(SurveyDataEndpointInterface::class);

    $dataEndpoint->shouldReceive('downloadData')
        ->withArgs(fn (string $surveyId, array $payload) => $payload['surveyVersion'] === 'v3')
        ->once()
        ->andReturn(['activityId' => 'activity-2']);

    $service = (new SurveyDataService($dataEndpoint, Mockery::mock(SurveyInterviewEndpointInterface::class)))
        ->setSurveyId('survey-1');

    $status = $service->downloadData(new SurveyDataRequestModel(surveyVersion: 'v3'));

    expect($status->activityId)->toBe('activity-2');
});

it('downloads and deletes the data of a single interview', function () {
    $dataEndpoint = Mockery::mock(SurveyDataEndpointInterface::class);
    $interviewEndpoint = Mockery::mock(SurveyInterviewEndpointInterface::class);

    $dataEndpoint->shouldReceive('downloadInterviewData')
        ->with('survey-1', 'interview-1', ['fileName' => 'one.zip'])
        ->once()
        ->andReturn(['activityId' => 'activity-3']);

    $interviewEndpoint->shouldReceive('deleteInterviewData')
        ->with('survey-1', 'interview-1')
        ->once()
        ->andReturn(['activityId' => 'activity-4']);

    $service = (new SurveyDataService($dataEndpoint, $interviewEndpoint))->setSurveyId('survey-1');

    expect($service->downloadInterviewData('interview-1', 'one.zip')->activityId)->toBe('activity-3')
        ->and($service->deleteInterviewData('interview-1')->activityId)->toBe('activity-4');
});

// ── Publish ──────────────────────────────────────────────────────────────

it('reads the publish state of both packages', function () {
    $endpoint = Mockery::mock(SurveyPublishEndpointInterface::class);

    $endpoint->shouldReceive('getPublishState')
        ->with('survey-1')
        ->once()
        ->andReturn(['live' => 1, 'test' => 0]);

    $state = (new SurveyPublishService($endpoint))->setSurveyId('survey-1')->getState();

    expect($state)->toBeInstanceOf(SurveyPublishStateModel::class)
        ->and($state->live)->toBe(SurveyPublishStateEnum::from(1))
        ->and($state->test)->toBe(SurveyPublishStateEnum::from(0));
});

it('sends the package type and upgrade flag as integers', function () {
    $endpoint = Mockery::mock(SurveyPublishEndpointInterface::class);

    $endpoint->shouldReceive('publish')
        ->with('survey-1', [
            'packageType' => SurveyPackageTypeEnum::Live->value,
            'forceUpgrade' => SurveyPublishForceUpgradeEnum::NoUpgrade->value,
        ])
        ->once();

    $endpoint->shouldReceive('publish')
        ->with('survey-1', [
            'packageType' => SurveyPackageTypeEnum::Live->value,
            'forceUpgrade' => SurveyPublishForceUpgradeEnum::ForceUpgrade->value,
        ])
        ->once();

    $endpoint->shouldReceive('publish')
        ->with('survey-1', [
            'packageType' => SurveyPackageTypeEnum::Test->value,
            'forceUpgrade' => SurveyPublishForceUpgradeEnum::NoUpgrade->value,
        ])
        ->once();

    $service = (new SurveyPublishService($endpoint))->setSurveyId('survey-1');

    $service->publishLive();
    $service->forcePublishLive();
    $service->publishTest();
});

it('starts a publish as a background activity', function () {
    $endpoint = Mockery::mock(SurveyPublishEndpointInterface::class);

    $endpoint->shouldReceive('startPublish')
        ->with('survey-1', [
            'packageType' => SurveyPackageTypeEnum::Live->value,
            'forceUpgrade' => SurveyPublishForceUpgradeEnum::NoUpgrade->value,
        ])
        ->once()
        ->andReturn(['activityId' => 'publish-1']);

    $endpoint->shouldReceive('startPublish')
        ->with('survey-1', [
            'packageType' => SurveyPackageTypeEnum::Live->value,
            'forceUpgrade' => SurveyPublishForceUpgradeEnum::ForceUpgrade->value,
        ])
        ->once()
        ->andReturn(['activityId' => 'publish-2']);

    $service = (new SurveyPublishService($endpoint))->setSurveyId('survey-1');

    expect($service->startPublishLive()->activityId)->toBe('publish-1')
        ->and($service->startForcePublishLive()->activityId)->toBe('publish-2');
});

// ── Public ids ───────────────────────────────────────────────────────────

it('lists public ids as models', function () {
    $endpoint = Mockery::mock(SurveyPublicIdsEndpointInterface::class);

    $endpoint->shouldReceive('list')->with('survey-1')->once()->andReturn([
        ['id' => 'p-1', 'linkType' => 'LiveId', 'url' => 'https://example.test/1', 'active' => true],
    ]);

    $ids = (new SurveyPublicIdsService($endpoint))->setSurveyId('survey-1')->list();

    expect($ids)->toBeInstanceOf(Collection::class)
        ->and($ids->first())->toBeInstanceOf(SurveyPublicIdModel::class)
        ->and($ids->first()->linkType)->toBe(SurveyPublicIdLinkTypeEnum::LiveId)
        ->and($ids->first()->active)->toBeTrue();
});

it('normalises every public id in an update through the model', function () {
    $endpoint = Mockery::mock(SurveyPublicIdsEndpointInterface::class);

    $endpoint->shouldReceive('update')
        ->withArgs(function (string $surveyId, array $payload) {
            return $surveyId === 'survey-1'
                && count($payload) === 2
                && $payload[0]['id'] === 'p-1'
                && $payload[1]['active'] === false;
        })
        ->once();

    (new SurveyPublicIdsService($endpoint))->setSurveyId('survey-1')->update([
        ['id' => 'p-1', 'linkType' => 'LiveId', 'url' => 'https://example.test/1', 'active' => true],
        new SurveyPublicIdModel('p-2', SurveyPublicIdLinkTypeEnum::InternalTestId, 'https://example.test/2'),
    ]);
});

// ── Sampling method ──────────────────────────────────────────────────────

it('reads and writes the sampling method', function () {
    $endpoint = Mockery::mock(SurveySamplingMethodEndpointInterface::class);

    $endpoint->shouldReceive('get')->with('survey-1')->once()->andReturn(['samplingMethod' => 'Random']);
    $endpoint->shouldReceive('update')->with('survey-1', ['samplingMethod' => 'Sequential'])->once();

    $service = (new SurveySamplingMethodService($endpoint))->setSurveyId('survey-1');

    $model = $service->getSamplingMethod();

    expect($model)->toBeInstanceOf(SamplingMethodModel::class)
        ->and($model->samplingMethod)->toBe('Random');

    $service->setSamplingMethod(['samplingMethod' => 'Sequential']);
});

// ── Settings ─────────────────────────────────────────────────────────────

it('lists settings as a collection of models', function () {
    $settings = Mockery::mock(SurveySettingsEndpointInterface::class);
    $general = Mockery::mock(SurveyGeneralSettingsEndpointInterface::class);

    $settings->shouldReceive('listSettings')->with('survey-1')->once()->andReturn([
        ['name' => 'InterviewerAuthentication', 'value' => 'true'],
        ['name' => 'AllowRefusal', 'value' => 'false'],
    ]);

    $list = (new SurveySettingsService($settings, $general))->setSurveyId('survey-1')->list();

    expect($list)->toBeInstanceOf(Collection::class)
        ->and($list)->toHaveCount(2)
        ->and($list->first())->toBeInstanceOf(SurveySettingModel::class)
        ->and($list->first()->name)->toBe('InterviewerAuthentication');
});

it('accepts a setting name as a string or as an enum', function () {
    $settings = Mockery::mock(SurveySettingsEndpointInterface::class);
    $general = Mockery::mock(SurveyGeneralSettingsEndpointInterface::class);

    $name = SurveySettingNameEnum::cases()[0];

    $settings->shouldReceive('addOrUpdateSetting')
        ->with('survey-1', ['name' => $name->value, 'value' => 'true'])
        ->twice()
        ->andReturn(['name' => $name->value, 'value' => 'true']);

    $service = (new SurveySettingsService($settings, $general))->setSurveyId('survey-1');

    expect($service->set($name, 'true')->value)->toBe('true')
        ->and($service->set($name->value, 'true')->name)->toBe($name->value);
});

it('reads and updates the general settings', function () {
    $settings = Mockery::mock(SurveySettingsEndpointInterface::class);
    $general = Mockery::mock(SurveyGeneralSettingsEndpointInterface::class);

    $general->shouldReceive('getGeneralSettings')->with('survey-1')->once()->andReturn([
        'name' => 'Demo',
        'client' => 'Acme',
        'description' => 'A demo',
        'excludeFromAutomaticCleanup' => false,
        'owner' => ['id' => 'user-1', 'userName' => 'ada'],
    ]);

    $general->shouldReceive('updateGeneralSettings')
        ->withArgs(fn (string $surveyId, array $payload) => $payload['name'] === 'Renamed')
        ->once();

    $service = (new SurveySettingsService($settings, $general))->setSurveyId('survey-1');

    $model = $service->getGeneral();

    expect($model)->toBeInstanceOf(SurveyGeneralSettingsModel::class)
        ->and($model->owner->userName)->toBe('ada');

    $service->updateGeneral(new SurveyGeneralSettingsUpdateModel(name: 'Renamed'));
});

// ── Mass assignment ──────────────────────────────────────────────────────

it('assigns and unassigns interviewers in bulk', function () {
    $endpoint = Mockery::mock(SurveySamplingPointsAssignmentsEndpointInterface::class);

    $payload = ['samplingPointIds' => ['sp-1', 'sp-2'], 'interviewerIds' => ['ivw-1']];

    $endpoint->shouldReceive('massAssign')->with('survey-1', $payload)->once()->andReturn($payload);
    $endpoint->shouldReceive('massUnassign')->with('survey-1', $payload)->once()->andReturn([]);

    $service = (new SurveyAssignmentService($endpoint))->setSurveyId('survey-1');

    $assigned = $service->assignInterviewers(['sp-1', 'sp-2'], ['ivw-1']);

    expect($assigned->samplingPointIds)->toBe(['sp-1', 'sp-2'])
        ->and($assigned->interviewerIds)->toBe(['ivw-1']);

    $service->unassignInterviewers(['sp-1', 'sp-2'], ['ivw-1']);
});

it('publishes an explicit package type and upgrade flag', function () {
    // The named helpers below it all funnel through this one.
    $endpoint = Mockery::mock(SurveyPublishEndpointInterface::class);

    $endpoint->shouldReceive('publish')
        ->with('survey-1', [
            'packageType' => SurveyPackageTypeEnum::Test->value,
            'forceUpgrade' => SurveyPublishForceUpgradeEnum::ForceUpgrade->value,
        ])
        ->once();

    $endpoint->shouldReceive('startPublish')
        ->with('survey-1', [
            'packageType' => SurveyPackageTypeEnum::Test->value,
            'forceUpgrade' => SurveyPublishForceUpgradeEnum::ForceUpgrade->value,
        ])
        ->once()
        ->andReturn(['activityId' => 'publish-3']);

    $service = (new SurveyPublishService($endpoint))->setSurveyId('survey-1');

    $service->publish(SurveyPackageTypeEnum::Test, SurveyPublishForceUpgradeEnum::ForceUpgrade);

    expect($service->start(SurveyPackageTypeEnum::Test, SurveyPublishForceUpgradeEnum::ForceUpgrade)->activityId)
        ->toBe('publish-3');
});
