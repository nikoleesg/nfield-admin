<?php

declare(strict_types=1);

use Illuminate\Http\Client\Response;
use Nikoleesg\NfieldAdmin\Endpoints\v2\SurveySettingsEndpoint;
use Nikoleesg\NfieldAdmin\Services\Http\HttpClient;

afterEach(function () {
    Mockery::close();
});

it('can list settings using the v2 path', function () {
    $httpClient = Mockery::mock(HttpClient::class);
    $endpoint = new SurveySettingsEndpoint($httpClient);

    $response = Mockery::mock(Response::class);
    $response->shouldReceive('json')->andReturn(['some' => 'data']);

    $httpClient->shouldReceive('get')
        ->with('v2/surveys/survey-id/settings')
        ->once()
        ->andReturn($response);

    $result = $endpoint->listSettings('survey-id');

    expect($result)->toBe(['some' => 'data']);
});

it('can add or update setting using the v2 path', function () {
    $httpClient = Mockery::mock(HttpClient::class);
    $endpoint = new SurveySettingsEndpoint($httpClient);

    $response = Mockery::mock(Response::class);
    $response->shouldReceive('json')->andReturn(['success' => true]);

    $httpClient->shouldReceive('post')
        ->with('v2/surveys/survey-id/settings', ['key' => 'value'])
        ->once()
        ->andReturn($response);

    $result = $endpoint->addOrUpdateSetting('survey-id', ['key' => 'value']);

    expect($result)->toBe(['success' => true]);
});
