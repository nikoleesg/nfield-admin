<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Nikoleesg\NfieldAdmin\Services\Http\HttpClient;

it('creates fresh pending requests so body and headers do not leak', function () {
    Http::fake([
        '*' => Http::response(['AccessToken' => 'test-token'], 200),
    ]);

    $client = app(HttpClient::class);

    // First request: postRaw
    $client->postRaw('/v2/surveys/1/sample', "a\tb", 'text/csv');

    // Second request: normal post
    $client->post('/v2/surveys', [['surveyName' => 'before']]);

    Http::assertSent(function (Request $request) {
        if (str_ends_with($request->url(), '/v2/surveys/1/sample')) {
            return $request->method() === 'POST'
                && $request->header('Content-Type')[0] === 'text/csv'
                && $request->body() === "a\tb";
        }

        if (str_ends_with($request->url(), '/v2/surveys')) {
            return $request->method() === 'POST'
                && $request->header('Content-Type')[0] === 'application/json'
                && $request->data() === [['surveyName' => 'before']];
        }

        return true; // token request
    });
});

it('works with Http::fake', function () {
    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'fake-token'], 200),
        '*/v2/test' => Http::response(['success' => true], 200),
    ]);

    $client = app(HttpClient::class);
    $response = $client->get('/v2/test');

    expect($response->json())->toBe(['success' => true]);

    Http::assertSent(function (Request $request) {
        return str_ends_with($request->url(), '/v2/test')
            && $request->header('Authorization')[0] === 'Bearer fake-token';
    });
});
