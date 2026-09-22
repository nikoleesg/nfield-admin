<?php

declare(strict_types=1);

use Illuminate\Http\Client\Response;
use Nikoleesg\NfieldAdmin\Endpoints\v2\SurveyGeneralSettingsEndpoint;
use Nikoleesg\NfieldAdmin\Services\Http\HttpClient;

afterEach(function () {
    Mockery::close();
});

it('can get general settings using the v2 path', function () {
    $httpClient = Mockery::mock(HttpClient::class);
    $endpoint = new SurveyGeneralSettingsEndpoint($httpClient);

    $response = Mockery::mock(Response::class);
    $response->shouldReceive('json')->andReturn(['general' => 'data']);

    $httpClient->shouldReceive('get')
        ->with('v2/surveys/survey-id/generalSettings')
        ->once()
        ->andReturn($response);

    $result = $endpoint->getGeneralSettings('survey-id');

    expect($result)->toBe(['general' => 'data']);
});

it('can update general settings using the v2 path', function () {
    $httpClient = Mockery::mock(HttpClient::class);
    $endpoint = new SurveyGeneralSettingsEndpoint($httpClient);

    $httpClient->shouldReceive('patch')
        ->with('v2/surveys/survey-id/generalSettings', ['patch' => 'data'])
        ->once();

    $endpoint->updateGeneralSettings('survey-id', ['patch' => 'data']);
});
