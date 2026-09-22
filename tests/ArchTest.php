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

it('keeps casing out of the DTO layer', function () {
    // #36: response keys are normalised once at the HTTP boundary, so no DTO
    // may carry a casing mapper. See ResponseKeyNormalizer.
    $offenders = [];

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(__DIR__.'/../src/Data', FilesystemIterator::SKIP_DOTS)
    );

    foreach ($files as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }

        if (preg_match('/#\[\s*Map(Input|Output)?Name\s*\(/', (string) file_get_contents($file->getPathname()))) {
            $offenders[] = basename($file->getPathname());
        }
    }

    expect($offenders)->toBe([]);
});
