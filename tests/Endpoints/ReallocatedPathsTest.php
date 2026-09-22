<?php

declare(strict_types=1);

use Illuminate\Http\Client\Response;
use Nikoleesg\NfieldAdmin\Endpoints\v2\SurveyEndpoint;
use Nikoleesg\NfieldAdmin\Endpoints\v2\SurveyInterviewEndpoint;
use Nikoleesg\NfieldAdmin\Endpoints\v2\SurveySampleCollectionEndpoint;
use Nikoleesg\NfieldAdmin\Endpoints\v2\SurveySampleDataDownloadEndpoint;
use Nikoleesg\NfieldAdmin\Services\Http\HttpClient;

/**
 * #40/#43 moved five operations between endpoint classes. The paths they build
 * must not have changed in the move.
 */
afterEach(function () {
    Mockery::close();
});

function jsonResponse(array $payload): Response
{
    $response = Mockery::mock(Response::class);
    $response->shouldReceive('json')->andReturn($payload);

    return $response;
}

it('activates sampling points from the survey endpoint', function () {
    $httpClient = Mockery::mock(HttpClient::class);

    $httpClient->shouldReceive('post')
        ->with('v2/surveys/survey-id/activateSamplingpoints', ['samplingPointIds' => ['sp-1', 'sp-2']])
        ->once()
        ->andReturn(jsonResponse(['ok' => true]));

    $result = (new SurveyEndpoint($httpClient))->batchActivateSamplingPoints('survey-id', ['samplingPointIds' => ['sp-1', 'sp-2']]);

    expect($result)->toBe(['ok' => true]);
});

it('deletes and updates sample from the collection endpoint', function () {
    $httpClient = Mockery::mock(HttpClient::class);

    $httpClient->shouldReceive('delete')
        ->with('v2/surveys/survey-id/sample', ['filter' => 'x'])
        ->once()
        ->andReturn(jsonResponse(['deleted' => 1]));

    $httpClient->shouldReceive('put')
        ->with('v2/surveys/survey-id/sample/update', ['column' => 'y'])
        ->once()
        ->andReturn(jsonResponse(['updated' => 1]));

    $endpoint = new SurveySampleCollectionEndpoint($httpClient);

    expect($endpoint->destroy('survey-id', ['filter' => 'x']))->toBe(['deleted' => 1])
        ->and($endpoint->update('survey-id', ['column' => 'y']))->toBe(['updated' => 1]);
});

it('requests a sample download from its own endpoint', function () {
    $httpClient = Mockery::mock(HttpClient::class);

    $httpClient->shouldReceive('post')
        ->with('v2/surveys/survey-id/sampleDataDownload/samples.csv', [])
        ->once()
        ->andReturn(jsonResponse(['activityId' => 'a-1']));

    $result = (new SurveySampleDataDownloadEndpoint($httpClient))->requestDownload('survey-id', 'samples.csv');

    expect($result)->toBe(['activityId' => 'a-1']);
});

it('deletes interview data from the interview endpoint', function () {
    $httpClient = Mockery::mock(HttpClient::class);

    $httpClient->shouldReceive('delete')
        ->with('v2/surveys/survey-id/interviews/interview-1')
        ->once()
        ->andReturn(jsonResponse(['deleted' => true]));

    $result = (new SurveyInterviewEndpoint($httpClient))->deleteInterviewData('survey-id', 'interview-1');

    expect($result)->toBe(['deleted' => true]);
});
