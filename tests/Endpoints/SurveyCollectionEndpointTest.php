<?php

declare(strict_types=1);

use Illuminate\Http\Client\Response;
use Nikoleesg\NfieldAdmin\Endpoints\v2\SurveyCollectionEndpoint;
use Nikoleesg\NfieldAdmin\Services\Http\HttpClient;

afterEach(function () {
    Mockery::close();
});

it('searches surveys with the spec query parameter name', function () {
    $httpClient = Mockery::mock(HttpClient::class);
    $endpoint = new SurveyCollectionEndpoint($httpClient);

    $response = Mockery::mock(Response::class);
    $response->shouldReceive('json')->andReturn([['SurveyId' => 'survey-id']]);

    $httpClient->shouldReceive('get')
        ->with('v2/surveys/search', ['value' => 'term'])
        ->once()
        ->andReturn($response);

    $result = $endpoint->search('term');

    expect($result)->toBe([['SurveyId' => 'survey-id']]);
});
