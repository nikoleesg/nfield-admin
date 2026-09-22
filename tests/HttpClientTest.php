<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
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

it('caches the access token on the default store, whatever the driver', function () {
    config()->set('cache.default', 'array');
    config()->set('nfield-admin.cache.enabled', true);
    config()->set('nfield-admin.cache.store', null);

    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'cached-token', 'ExpiresIn' => 3600], 200),
        '*/v2/test' => Http::response(['success' => true], 200),
    ]);

    $client = app(HttpClient::class);
    $client->get('/v2/test');
    $client->get('/v2/test');

    expect(Cache::get('nfield_access_token'))->toBe(['accessToken' => 'cached-token']);

    Http::assertSentCount(3); // one token request, two API calls
});

it('honours an explicitly configured cache store', function () {
    config()->set('cache.default', 'null');
    config()->set('nfield-admin.cache.store', 'array');

    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'store-token', 'ExpiresIn' => 3600], 200),
        '*/v2/test' => Http::response(['success' => true], 200),
    ]);

    $client = app(HttpClient::class);
    $client->get('/v2/test');
    $client->get('/v2/test');

    expect(Cache::store('array')->get('nfield_access_token'))->toBe(['accessToken' => 'store-token']);

    Http::assertSentCount(3);
});

it('does not cache when caching is disabled', function () {
    config()->set('cache.default', 'array');
    config()->set('nfield-admin.cache.enabled', false);

    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'uncached-token', 'ExpiresIn' => 3600], 200),
        '*/v2/test' => Http::response(['success' => true], 200),
    ]);

    $client = app(HttpClient::class);
    $client->get('/v2/test');
    $client->get('/v2/test');

    expect(Cache::store('array')->get('nfield_access_token'))->toBeNull();

    Http::assertSentCount(4); // a token request before each API call
});
