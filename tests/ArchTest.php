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

/**
 * #40/#43: one endpoint class per spec path prefix.
 *
 * The `EndpointPath` trait already encodes the distinction the rule turns on:
 * `resourcePath`/`resourceActionPath`/`actionPath` address the class's *own*
 * resource, while the `subResource*`/`nested*` helpers address a child of it.
 * So the rule can be checked mechanically from the helper each `$uri = ...`
 * line uses, plus the literal segment it passes.
 */
function endpointPathUsage(string $file): array
{
    $source = (string) file_get_contents($file);

    $own = [];
    $subResources = [];
    $nestedResources = [];
    $itemPaths = false;

    preg_match_all(
        '/\$this->(basePath|resourcePath|actionPath|resourceActionPath|subResourcePath|subResourceActionPath|subResourceItemPath|subResourceItemActionPath|nestedResourcePath|nestedResourceItemPath)\(([^;]*)\)/',
        $source,
        $matches,
        PREG_SET_ORDER
    );

    foreach ($matches as [, $helper, $args]) {
        $literals = [];
        preg_match_all("/'([^']*)'/", $args, $found);
        foreach ($found[1] as $literal) {
            $literals[] = explode('/', $literal)[0];
        }

        switch ($helper) {
            case 'basePath':
            case 'resourcePath':
                $own[] = $helper;
                break;
            case 'actionPath':
            case 'resourceActionPath':
                $own[] = $helper;
                break;
            case 'subResourceItemPath':
            case 'subResourceItemActionPath':
                $itemPaths = true;
                // fall through
            case 'subResourcePath':
            case 'subResourceActionPath':
                $subResources[] = $literals[0] ?? '?';
                break;
            case 'nestedResourceItemPath':
                $itemPaths = true;
                // fall through
            case 'nestedResourcePath':
                $subResources[] = $literals[0] ?? '?';
                $nestedResources[] = $literals[1] ?? '?';
                break;
        }
    }

    return [
        'own' => array_values(array_unique($own)),
        'subResources' => array_values(array_unique($subResources)),
        'nestedResources' => array_values(array_unique($nestedResources)),
        'itemPaths' => $itemPaths,
    ];
}

it('keeps every endpoint class inside one spec path prefix', function () {
    $offenders = [];

    foreach (glob(__DIR__.'/../src/Endpoints/v2/*.php') as $file) {
        $name = basename($file, '.php');

        if ($name === 'BaseEndpoint') {
            continue;
        }

        $usage = endpointPathUsage($file);

        // One sub-resource per class: `{surveyId}/quotaTargets` and
        // `{surveyId}/surveyQuotaFrame` are two spec paths, not one.
        if (count($usage['subResources']) > 1) {
            $offenders[] = $name.' spans sub-resources: '.implode(', ', $usage['subResources']);
        }

        if (count($usage['nestedResources']) > 1) {
            $offenders[] = $name.' spans nested resources: '.implode(', ', $usage['nestedResources']);
        }

        // A class that owns a sub-resource does not also act on its parent —
        // that is how `activateSamplingpoints` ended up on a sampling point class.
        if ($usage['subResources'] !== [] && $usage['own'] !== []) {
            $offenders[] = $name.' mixes sub-resource paths with parent-resource paths ('.implode(', ', $usage['own']).')';
        }

        // Collection classes address the collection, never an item inside it.
        if (str_ends_with($name, 'CollectionEndpoint') && $usage['itemPaths']) {
            $offenders[] = $name.' builds item paths; those belong in the item class';
        }
    }

    expect($offenders)->toBe([]);
});

it('keeps the API version in one place', function () {
    $offenders = [];

    foreach (glob(__DIR__.'/../src/Endpoints/v2/*.php') as $file) {
        $name = basename($file, '.php');
        $source = (string) file_get_contents($file);

        if ($name !== 'BaseEndpoint' && preg_match('/protected\s+string\s+\$version/', $source)) {
            $offenders[] = $name.' redeclares $version; it belongs on BaseEndpoint';
        }

        // #35: a hardcoded '/v2' prefix drifts the moment a v3 appears.
        if (preg_match('#[\'"]/v\d+/#', $source)) {
            $offenders[] = $name.' hardcodes the version prefix instead of using $this->version';
        }
    }

    expect($offenders)->toBe([]);
});
