<?php

declare(strict_types=1);

use Illuminate\Http\Client\Response;
use Nikoleesg\NfieldAdmin\Endpoints\v2\BackgroundActivitiesEndpoint;
use Nikoleesg\NfieldAdmin\Services\Http\HttpClient;

afterEach(function () {
    Mockery::close();
});

it('uses the spec path casing for background activities', function () {
    $httpClient = Mockery::mock(HttpClient::class);
    $endpoint = new BackgroundActivitiesEndpoint($httpClient);

    $response = Mockery::mock(Response::class);
    $response->shouldReceive('json')->andReturn(['ActivityId' => 'activity-id']);

    $httpClient->shouldReceive('get')
        ->with('v2/BackgroundActivities/activity-id')
        ->once()
        ->andReturn($response);

    $result = $endpoint->get('activity-id');

    expect($result)->toBe(['ActivityId' => 'activity-id']);
});
