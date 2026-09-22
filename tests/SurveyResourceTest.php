<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyDataRequestModel;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;
use Nikoleesg\NfieldAdmin\Services\SurveyDataService;
use Nikoleesg\NfieldAdmin\Services\SurveyFieldworkService;

afterEach(function () {
    Mockery::close();
});

it('can request data download', function () {
    $endpoint = Mockery::mock(SurveyEndpointInterface::class);
    $resource = (new SurveyResource($endpoint))->setSurveyId('survey-1');

    $surveyDataService = Mockery::mock(SurveyDataService::class);

    $requestModel = new SurveyDataRequestModel;

    $status = new BackgroundActivityStatus('act-1');

    $surveyDataService->shouldReceive('setSurveyId')->with('survey-1')->once();

    $surveyDataService->shouldReceive('downloadData')
        ->with($requestModel)
        ->once()
        ->andReturn($status);

    app()->bind(SurveyDataService::class, fn () => $surveyDataService);

    $result = $resource->requestDataDownload($requestModel);

    expect($result)->toBe($status);
});

it('hands its scope to every service it resolves', function () {
    // #41: the scope used to travel as a container argument keyed by the
    // service's constructor parameter name. It now travels as a setter call.
    $resource = (new SurveyResource(Mockery::mock(SurveyEndpointInterface::class)))
        ->setSurveyId('survey-1');

    expect($resource->fieldwork()->getSurveyId())->toBe('survey-1');
});

it('rescopes the services it has already resolved', function () {
    $resource = (new SurveyResource(Mockery::mock(SurveyEndpointInterface::class)))
        ->setSurveyId('survey-1');

    $first = $resource->fieldwork();

    expect($resource->fieldwork())->toBe($first)
        ->and($resource->setSurveyId('survey-2')->fieldwork()->getSurveyId())->toBe('survey-2')
        ->and($first->getSurveyId())->toBe('survey-1');
});

it('refuses to build a request before the scope is set', function () {
    // Constructor injection used to make the scope impossible to omit; the
    // setters trade that for a loud failure at first use.
    $service = app(SurveyFieldworkService::class);

    expect(fn () => $service->start())->toThrow(MissingScopeException::class);
});
