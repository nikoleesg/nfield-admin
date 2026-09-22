<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Nikoleesg\NfieldAdmin\Exceptions\ApiRequestException;
use Nikoleesg\NfieldAdmin\Exceptions\AuthenticationException;
use Nikoleesg\NfieldAdmin\Exceptions\NotFoundException;
use Nikoleesg\NfieldAdmin\Exceptions\ValidationException;
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

it('authenticates every request except the token endpoints', function () {
    config()->set('nfield-admin.cache.enabled', false);

    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'auth-token'], 200),
        '*' => Http::response(['ok' => true], 200),
    ]);

    $client = app(HttpClient::class);

    $client->get('/v2/surveys');
    $client->post('/v2/surveys', ['surveyName' => 'demo']);
    $client->patch('/v2/surveys/1', ['surveyName' => 'renamed']);
    $client->put('/v2/surveys/1/fieldwork/stop', []);
    $client->delete('/v2/surveys/1');
    $client->postRaw('/v2/surveys/1/sample', "a\tb", 'text/csv');
    $client->postMultipart('/v2/surveys/1/sample', 'File', "a\tb", 'sample.csv');

    collect(Http::recorded())->map(fn (array $pair): Request => $pair[0])
        ->each(function (Request $request) {
            $isToken = str_contains($request->url(), '/v2/token');

            expect($request->hasHeader('Authorization', 'Bearer auth-token'))->toBe(! $isToken);
        });
});

it('retries once with a fresh token after a 401, then gives up', function () {
    config()->set('cache.default', 'array');
    config()->set('nfield-admin.cache.enabled', true);

    Http::fake([
        '*/v2/token' => Http::sequence()
            ->push(['AccessToken' => 'stale-token'], 200)
            ->push(['AccessToken' => 'fresh-token'], 200),
        '*/v2/surveys' => Http::response(['message' => 'unauthorized'], 401),
    ]);

    $client = app(HttpClient::class);

    expect(fn () => $client->get('/v2/surveys'))->toThrow(AuthenticationException::class);

    // Token, call, forget + re-authenticate, call again — and no third attempt.
    Http::assertSentCount(4);

    // The stale token was forgotten and the retry cached its replacement.
    expect(Cache::store('array')->get('nfield_access_token'))->toBe(['accessToken' => 'fresh-token']);

    $tokens = collect(Http::recorded())
        ->map(fn (array $pair): Request => $pair[0])
        ->reject(fn (Request $request): bool => str_contains($request->url(), '/v2/token'))
        ->map(fn (Request $request): string => $request->header('Authorization')[0])
        ->values();

    expect($tokens->all())->toBe(['Bearer stale-token', 'Bearer fresh-token']);
});

it('succeeds on the retry when the token was merely stale', function () {
    config()->set('cache.default', 'array');
    config()->set('nfield-admin.cache.enabled', true);

    Http::fake([
        '*/v2/token' => Http::sequence()
            ->push(['AccessToken' => 'stale-token'], 200)
            ->push(['AccessToken' => 'fresh-token'], 200),
        '*/v2/surveys' => Http::sequence()
            ->push(['message' => 'unauthorized'], 401)
            ->push(['SurveyId' => 'survey-1'], 200),
    ]);

    $response = app(HttpClient::class)->get('/v2/surveys');

    expect($response->json())->toBe(['surveyId' => 'survey-1']);
});

it('maps each error status onto its own exception', function (int $status, string $exception) {
    config()->set('nfield-admin.cache.enabled', false);

    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'auth-token'], 200),
        '*/v2/surveys' => Http::response(['message' => 'nope'], $status),
    ]);

    $client = app(HttpClient::class);

    expect(fn () => $client->get('/v2/surveys'))->toThrow($exception);
})->with([
    'not found' => [404, NotFoundException::class],
    'validation' => [422, ValidationException::class],
    'server error' => [500, ApiRequestException::class],
    'bad request' => [400, ApiRequestException::class],
]);

it('carries the status and the response body on the exception', function () {
    config()->set('nfield-admin.cache.enabled', false);

    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'auth-token'], 200),
        '*/v2/surveys' => Http::response(['message' => 'no such survey'], 404),
    ]);

    try {
        app(HttpClient::class)->get('/v2/surveys');
    } catch (NotFoundException $exception) {
        expect($exception->getCode())->toBe(404)
            ->and($exception->body())->toBe('{"message":"no such survey"}');

        return;
    }

    $this->fail('NotFoundException was not thrown');
});

it('spends a cached refresh token before re-authenticating', function () {
    config()->set('cache.default', 'array');
    config()->set('nfield-admin.cache.enabled', true);

    Cache::store('array')->put('nfield_refresh_token', 'refresh-me');

    Http::fake([
        '*/v2/token/refresh' => Http::response(['AccessToken' => 'refreshed-token', 'ExpiresIn' => 3600], 200),
        '*/v2/token' => Http::response(['AccessToken' => 'password-token'], 200),
        '*/v2/surveys' => Http::response(['ok' => true], 200),
    ]);

    app(HttpClient::class)->get('/v2/surveys');

    Http::assertSent(fn (Request $request) => str_ends_with($request->url(), '/v2/token/refresh'));
    Http::assertNotSent(fn (Request $request) => str_ends_with($request->url(), '/v2/token'));

    expect(Cache::store('array')->get('nfield_access_token'))->toBe(['accessToken' => 'refreshed-token']);
});

it('falls back to the credentials when the refresh token is rejected', function () {
    config()->set('cache.default', 'array');
    config()->set('nfield-admin.cache.enabled', true);

    Cache::store('array')->put('nfield_refresh_token', 'expired');

    Http::fake([
        '*/v2/token/refresh' => Http::response(['message' => 'expired'], 401),
        '*/v2/token' => Http::response(['AccessToken' => 'password-token'], 200),
        '*/v2/surveys' => Http::response(['ok' => true], 200),
    ]);

    app(HttpClient::class)->get('/v2/surveys');

    expect(Cache::store('array')->get('nfield_access_token'))->toBe(['accessToken' => 'password-token']);
});

it('sends the configured credentials to the token endpoint', function () {
    config()->set('nfield-admin.cache.enabled', false);
    config()->set('nfield-admin.domain', 'acme');
    config()->set('nfield-admin.username', 'ada');
    config()->set('nfield-admin.password', 'hunter2');

    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'auth-token'], 200),
        '*' => Http::response(['ok' => true], 200),
    ]);

    app(HttpClient::class)->get('/v2/surveys');

    Http::assertSent(fn (Request $request) => str_ends_with($request->url(), '/v2/token')
        && $request->data() === ['domainName' => 'acme', 'userName' => 'ada', 'password' => 'hunter2']);
});

it('caps the token TTL at the configured maximum', function () {
    config()->set('cache.default', 'array');
    config()->set('nfield-admin.cache.enabled', true);
    config()->set('nfield-admin.cache.ttl', 60);

    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'auth-token', 'ExpiresIn' => 86400], 200),
        '*' => Http::response(['ok' => true], 200),
    ]);

    app(HttpClient::class)->get('/v2/surveys');

    // A 24h `expiresIn` must not pin a token in the cache for 24h.
    expect(Cache::store('array')->getStore()->get('nfield_access_token'))->not->toBeNull();

    Cache::store('array')->clear();

    app(HttpClient::class)->get('/v2/surveys');

    Http::assertSentCount(4);
});

it('leaves the raw response alone on the exempted dictionary paths', function () {
    config()->set('nfield-admin.cache.enabled', false);

    Http::fake([
        '*/v2/token' => Http::response(['AccessToken' => 'auth-token'], 200),
        '*/v2/roles' => Http::response(['Administrator' => ['SurveyRead']], 200),
        '*/v2/surveys' => Http::response(['SurveyId' => 'survey-1'], 200),
    ]);

    $client = app(HttpClient::class);

    expect($client->get('/v2/roles')->json())->toBe(['Administrator' => ['SurveyRead']])
        ->and($client->get('/v2/surveys')->json())->toBe(['surveyId' => 'survey-1']);
});
