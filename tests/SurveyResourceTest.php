<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyDataRequestModel;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;
use Nikoleesg\NfieldAdmin\Services\SurveyDataService;

afterEach(function () {
    Mockery::close();
});

it('can request data download', function () {
    $endpoint = Mockery::mock(SurveyEndpointInterface::class);
    $resource = (new SurveyResource($endpoint))->setSurveyId('survey-1');

    $surveyDataService = Mockery::mock(SurveyDataService::class);
    $surveyDataService->shouldReceive('setSurveyId')
        ->with('survey-1')
        ->once()
        ->andReturnSelf();

    $requestModel = new SurveyDataRequestModel;

    $status = new BackgroundActivityStatus('act-1');
    // BackgroundActivityStatus might require constructor arguments. Let's check how it's structured.

    $surveyDataService->shouldReceive('downloadData')
        ->with($requestModel)
        ->once()
        ->andReturn($status);

    app()->instance(SurveyDataService::class, $surveyDataService);

    $result = $resource->requestDataDownload($requestModel);

    expect($result)->toBe($status);
});
