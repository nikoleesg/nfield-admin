<?php

declare(strict_types=1);

use Nikoleesg\NfieldAdmin\Resources\SurveyResource;

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
