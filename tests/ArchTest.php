<?php

declare(strict_types=1);

it('will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

it('ensures v2 endpoints are correctly structured', function () {
    $files = glob(__DIR__.'/../src/Endpoints/v2/*.php');
    foreach ($files as $file) {
        if (basename($file) === 'BaseEndpoint.php') {
            continue;
        }

        $class = 'Nikoleesg\NfieldAdmin\Endpoints\v2\\'.basename($file, '.php');
        $reflection = new ReflectionClass($class);

        expect($reflection->isFinal())->toBeTrue()
            ->and($reflection->isSubclassOf('Nikoleesg\NfieldAdmin\Endpoints\v2\BaseEndpoint'))->toBeTrue();

        $interfaces = $reflection->getInterfaces();
        $endpointInterfaces = array_filter($interfaces, function ($interface) {
            return str_starts_with($interface->getName(), 'Nikoleesg\NfieldAdmin\Contracts\Endpoints\\');
        });

        expect(count($endpointInterfaces))->toBe(1);
    }
});
