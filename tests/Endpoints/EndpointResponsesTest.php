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
