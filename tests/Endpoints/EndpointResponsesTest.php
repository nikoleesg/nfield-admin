<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints as Contracts;

/**
 * The endpoint calls whose *response* shape matters, so they need their own
 * stubs rather than the catch-all `EndpointRequestsTest` registers.
 */
it('unwraps the OData envelope on the CAPI list endpoints', function () {
    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'endpoint-test-token'], 200),
        '*/v2/capiInterviewers/ivw-1/assignments' => Http::response(['value' => [['SurveyId' => 's-1']]], 200),
        '*/v2/capiInterviewers' => Http::response(['value' => [['InterviewerId' => 'ivw-1']]], 200),
    ]);

    expect(app(Contracts\CapiInterviewersCollectionEndpointInterface::class)->list())
        ->toBe([['interviewerId' => 'ivw-1']])
        ->and(app(Contracts\CapiInterviewersAssignmentsEndpointInterface::class)->list('ivw-1'))
        ->toBe([['surveyId' => 's-1']]);
});

it('accepts the OData envelope on the survey response code list', function () {
    // #65: the spec advertises OData content types for this list, which can
    // wrap the rows in {"value": [...]}; a bare array must work too.
    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'endpoint-test-token'], 200),
        '*/v2/surveys/enveloped/responseCodes' => Http::response(['value' => [['ResponseCode' => 210]]], 200),
        '*/v2/surveys/bare/responseCodes' => Http::response([['ResponseCode' => 211]], 200),
    ]);

    $endpoint = app(Contracts\SurveyResponseCodeCollectionEndpointInterface::class);

    expect($endpoint->list('enveloped'))->toBe([['responseCode' => 210]])
        ->and($endpoint->list('bare'))->toBe([['responseCode' => 211]]);
});

it('always returns a list from the request configurations endpoint', function () {
    // #57: the spec documents GET /v2/requests as returning a single
    // RequestModel, yet describes it as a list with an optional ?name filter.
    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'endpoint-test-token'], 200),
        '*/v2/requests?name=single' => Http::response(['Id' => 1, 'Name' => 'single'], 200),
        '*/v2/requests?name=envelope' => Http::response(['value' => [['Id' => 2, 'Name' => 'envelope']]], 200),
        '*/v2/requests?name=none' => Http::response([], 200),
        '*/v2/requests' => Http::response([['Id' => 3, 'Name' => 'a'], ['Id' => 4, 'Name' => 'b']], 200),
    ]);

    $endpoint = app(Contracts\RequestConfigurationCollectionEndpointInterface::class);

    expect($endpoint->list(['name' => 'single']))->toBe([['id' => 1, 'name' => 'single']])
        ->and($endpoint->list(['name' => 'envelope']))->toBe([['id' => 2, 'name' => 'envelope']])
        ->and($endpoint->list(['name' => 'none']))->toBe([])
        ->and($endpoint->list())->toHaveCount(2);
});

it('returns the sample download untouched by the key normalizer', function () {
    // A sample download is TSV, not JSON: `body()` must survive the response
    // wrapper the normalization added in #36.
    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'endpoint-test-token'], 200),
        '*' => Http::response("InterviewId\tName\n1\tAda", 200),
    ]);

    expect(app(Contracts\SurveySampleCollectionEndpointInterface::class)->download('survey-1'))
        ->toBe("InterviewId\tName\n1\tAda")
        ->and(app(Contracts\SurveySampleEndpointInterface::class)->get('survey-1', 1))
        ->toBe("InterviewId\tName\n1\tAda");
});
