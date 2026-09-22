<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Contracts\Http\HttpClientInterface;
use Nikoleesg\NfieldAdmin\Facades\NfieldManager;
use Nikoleesg\NfieldAdmin\Resources\SamplingPointResource;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;
use Nikoleesg\NfieldAdmin\Services\Http\HttpClient;
use Nikoleesg\NfieldAdmin\Services\NfieldManagerService;

/**
 * Splitting an endpoint class is only safe if the container can still build
 * everything that consumes it — the services below gained constructor
 * dependencies in #40/#43 and are auto-wired, so nothing else catches a
 * missing binding.
 */
it('binds every endpoint contract', function () {
    foreach (glob(__DIR__.'/../../src/Contracts/Endpoints/*.php') as $file) {
        $contract = 'Nikoleesg\NfieldAdmin\Contracts\Endpoints\\'.basename($file, '.php');

        expect(app($contract))->toBeInstanceOf($contract);
    }
});

it('resolves every service reachable from a survey resource', function () {
    $resource = app(SurveyResource::class)->setSurveyId('survey-id');

    $accessors = ['samplingPoints', 'assignments', 'fieldwork', 'data', 'samples', 'samplingMethod', 'quota', 'settings', 'publish', 'publicIds'];

    foreach ($accessors as $accessor) {
        expect($resource->{$accessor}())->toBeObject();
    }
});

it('binds every endpoint contract to the class named after it', function () {
    // A binding pointing at the wrong implementation resolves fine and then
    // builds the wrong paths, so the name is asserted, not just the type.
    foreach (glob(__DIR__.'/../../src/Contracts/Endpoints/*.php') as $file) {
        $contract = 'Nikoleesg\NfieldAdmin\Contracts\Endpoints\\'.basename($file, '.php');
        $expected = 'Nikoleesg\NfieldAdmin\Endpoints\v2\\'.preg_replace('/Interface$/', '', basename($file, '.php'));

        expect(app($contract))->toBeInstanceOf($expected);
    }
});

it('binds the HTTP client contract', function () {
    expect(app(HttpClientInterface::class))->toBeInstanceOf(HttpClient::class);
});

it('resolves every service and resource from the container', function () {
    // Every service is auto-wired off its endpoint contracts; a missing
    // binding surfaces here rather than at the first call.
    foreach (['Services', 'Resources'] as $layer) {
        foreach (glob(__DIR__.'/../../src/'.$layer.'/*.php') as $file) {
            $class = 'Nikoleesg\NfieldAdmin\\'.$layer.'\\'.basename($file, '.php');

            expect(app($class))->toBeInstanceOf($class);
        }
    }
});

it('resolves every service reachable from a sampling point resource', function () {
    $resource = app(SamplingPointResource::class)
        ->setSurveyId('survey-1')
        ->setSamplingPointId('sp-1');

    foreach (['addresses', 'assignments', 'quotaTargets'] as $accessor) {
        expect($resource->{$accessor}())->toBeObject();
    }
});

it('resolves the manager through its facade and its alias', function () {
    expect(app('nfield-manager'))->toBeInstanceOf(NfieldManagerService::class)
        ->and(NfieldManager::getFacadeRoot())->toBeInstanceOf(NfieldManagerService::class);
});
