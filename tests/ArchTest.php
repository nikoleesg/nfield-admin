<?php

declare(strict_types=1);
use Nikoleesg\NfieldAdmin\Facades\NfieldManager;
use Nikoleesg\NfieldAdmin\Services\NfieldManagerService;

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

/**
 * #37/#38: the service layer is the public SDK surface.
 *
 * Every public method on a service or resource hands back a DTO, an
 * `Illuminate\Support\Collection` of DTOs, or nothing — never a raw `array`
 * the caller has to know the wire format of, and never a
 * `Spatie\LaravelData\DataCollection`, which Spatie has been steering users
 * away from since v4.
 */
function publicSdkClasses(): array
{
    $classes = [];

    foreach (['Services', 'Resources'] as $layer) {
        foreach (glob(__DIR__.'/../src/'.$layer.'/*.php') as $file) {
            $classes[] = 'Nikoleesg\NfieldAdmin\\'.$layer.'\\'.basename($file, '.php');
        }
    }

    return $classes;
}

function publicSdkMethods(): array
{
    $methods = [];

    foreach (publicSdkClasses() as $class) {
        $reflection = new ReflectionClass($class);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->isConstructor() || $method->getDeclaringClass()->getName() !== $class) {
                continue;
            }

            $methods[] = [$class, $method];
        }
    }

    return $methods;
}

/**
 * Every scoping contract, mapped to the scope it carries (`getSurveyId()` ->
 * `surveyId`). Read from src/Contracts/Scoping so a new scope is covered by
 * the scoping rules below without editing them.
 *
 * @return array<class-string, string>
 */
function scopingContracts(): array
{
    $contracts = [];

    foreach (glob(__DIR__.'/../src/Contracts/Scoping/*.php') as $file) {
        $contract = 'Nikoleesg\NfieldAdmin\Contracts\Scoping\\'.basename($file, '.php');

        foreach ((new ReflectionClass($contract))->getMethods() as $method) {
            if ($method->getDeclaringClass()->getName() === $contract && str_starts_with($method->getName(), 'get')) {
                $contracts[$contract] = lcfirst(substr($method->getName(), 3));
            }
        }
    }

    return $contracts;
}

it('never returns a raw array from the public SDK surface', function () {
    $offenders = [];

    foreach (publicSdkMethods() as [$class, $method]) {
        $returnType = $method->getReturnType();

        if ($returnType instanceof ReflectionNamedType && $returnType->getName() === 'array') {
            $offenders[] = class_basename($class).'::'.$method->getName().'() returns array';
        }
    }

    expect($offenders)->toBe([]);
});

it('never returns a DataCollection from the public SDK surface', function () {
    $offenders = [];

    foreach (publicSdkMethods() as [$class, $method]) {
        $returnType = $method->getReturnType();

        if ($returnType instanceof ReflectionNamedType && $returnType->getName() === 'Spatie\LaravelData\DataCollection') {
            $offenders[] = class_basename($class).'::'.$method->getName().'() returns DataCollection';
        }
    }

    expect($offenders)->toBe([]);
});

it('carries an element-type generic on every list method', function () {
    // A bare `Collection` return tells PHPStan and the IDE nothing about what
    // is inside it, which is the whole point of returning DTOs.
    $offenders = [];

    foreach (publicSdkMethods() as [$class, $method]) {
        $returnType = $method->getReturnType();

        if (! $returnType instanceof ReflectionNamedType || $returnType->getName() !== 'Illuminate\Support\Collection') {
            continue;
        }

        if (! preg_match('/@return\s+Collection<[^>]+>/', (string) $method->getDocComment())) {
            $offenders[] = class_basename($class).'::'.$method->getName().'() is missing @return Collection<int, Model>';
        }
    }

    expect($offenders)->toBe([]);
});

/**
 * #41: the resource layer is the fluent entry point, and it had no single
 * pattern to copy. These four rules are that pattern.
 */
it('never scopes a service by constructor parameter name', function () {
    // `app($service, ['surveyId' => ...])` matched the container's arguments
    // against the literal parameter name, so renaming the parameter silently
    // stopped the injection. Scope now travels through the *ScopedInterface
    // setters instead.
    $offenders = [];

    foreach (publicSdkClasses() as $class) {
        $constructor = (new ReflectionClass($class))->getConstructor();

        if ($constructor === null) {
            continue;
        }

        foreach ($constructor->getParameters() as $parameter) {
            if (in_array($parameter->getName(), scopingContracts(), true)) {
                $offenders[] = class_basename($class).'::__construct() takes $'.$parameter->getName().'; use the scoping contract';
            }
        }
    }

    expect($offenders)->toBe([]);
});

it('declares the scoping contract wherever a scope is read', function () {
    $offenders = [];

    foreach (publicSdkClasses() as $class) {
        $source = (string) file_get_contents((new ReflectionClass($class))->getFileName());

        foreach (scopingContracts() as $contract => $scope) {
            if (str_contains($source, '$this->get'.ucfirst($scope).'()')
                && ! is_subclass_of($class, $contract)) {
                $offenders[] = class_basename($class).' reads the '.$scope.' scope without implementing '.class_basename($contract);
            }
        }
    }

    expect($offenders)->toBe([]);
});

it('keeps scope state in the ScopedTo* traits', function () {
    // #55: resources that held their own nullable `$interviewId`,
    // `$blueprintId`, `$addressId`, ... passed null straight to the endpoint
    // (a TypeError, or a request to `/sample/`) instead of failing with
    // MissingScopeException. The traits' getters are the only guard.
    $offenders = [];

    foreach (publicSdkClasses() as $class) {
        $reflection = new ReflectionClass($class);
        $fromTraits = [];

        foreach ($reflection->getTraits() as $trait) {
            foreach ($trait->getProperties() as $property) {
                $fromTraits[] = $property->getName();
            }
        }

        foreach ($reflection->getProperties() as $property) {
            if ($property->getDeclaringClass()->getName() === $class
                && in_array($property->getName(), scopingContracts(), true)
                && ! in_array($property->getName(), $fromTraits, true)) {
                $offenders[] = class_basename($class).'::$'.$property->getName().' is hand-rolled; use the ScopedTo* trait';
            }
        }
    }

    expect($offenders)->toBe([]);
});

it('hands every scope on when resolving a scoped service', function () {
    $source = (string) file_get_contents(__DIR__.'/../src/Traits/ResolvesScopedServices.php');
    $offenders = [];

    foreach (scopingContracts() as $contract => $scope) {
        $name = class_basename($contract);

        if (! str_contains($source, '$service instanceof '.$name.' && $this instanceof '.$name)) {
            $offenders[] = $name.' is not handed on by resolveService()';
        }

        if (! str_contains($source, "if (\$this instanceof {$name}) {\n            \$key .= '|'.\$this->get".ucfirst($scope).'();')) {
            $offenders[] = $name.' is not part of scopedServiceKey()';
        }
    }

    expect($offenders)->toBe([]);
});

it('returns static from every fluent setter', function () {
    // `static` is the only correct return type for a chainable setter: `self`
    // and a hardcoded class name both lie under inheritance.
    $offenders = [];

    foreach (publicSdkMethods() as [$class, $method]) {
        $returnType = $method->getReturnType();

        if (! $returnType instanceof ReflectionNamedType) {
            continue;
        }

        // A method that hands back its own class is a chainable setter,
        // however it spells that: `self`, or the class name written out.
        if (in_array($returnType->getName(), ['self', $class], true)) {
            $offenders[] = class_basename($class).'::'.$method->getName().'() returns '.class_basename($returnType->getName()).', not static';
        }
    }

    expect($offenders)->toBe([]);
});

it('exposes one name per fluent entry point', function () {
    // `for()` and `forSurvey()` were two names for one call. The explicit form
    // is self-documenting at a call site; the bare one is ambiguous when chained.
    $offenders = [];

    foreach (publicSdkMethods() as [$class, $method]) {
        if ($method->getName() === 'for') {
            $offenders[] = class_basename($class).'::for() — name the resource it returns, e.g. forSurvey()';
        }
    }

    expect($offenders)->toBe([]);
});

/**
 * #29: rules that keep the removed v1 stack, the global facade aliases and
 * unfinished work from creeping back into `src/`.
 */
function sourceFiles(): array
{
    $files = [];

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(__DIR__.'/../src', FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->getExtension() === 'php') {
            $files[] = $file->getPathname();
        }
    }

    sort($files);

    return $files;
}

it('keeps every trace of the v1 stack out of src', function () {
    // v2 only since 2.0: there is no `Endpoints\v1` or `Services\v1` to
    // depend on, and nothing may reintroduce one.
    $offenders = [];

    foreach (sourceFiles() as $file) {
        if (str_contains($file, '/v1/')) {
            $offenders[] = substr($file, strlen(dirname(__DIR__)) + 1).' lives under a v1 directory';

            continue;
        }

        if (preg_match('/\\\\v1\\\\/', (string) file_get_contents($file))) {
            $offenders[] = substr($file, strlen(dirname(__DIR__)) + 1).' references a v1 namespace';
        }
    }

    expect($offenders)->toBe([]);
});

it('imports facades by their full namespace, never the root alias', function () {
    // `\Log` and `\Http` only resolve when the host application registers the
    // aliases, which a package cannot assume.
    $offenders = [];

    foreach (sourceFiles() as $file) {
        $source = (string) file_get_contents($file);

        if (preg_match('/^use (Log|Http|Cache|Config)\s*;/m', $source, $matches)) {
            $offenders[] = basename($file).' imports the root alias '.$matches[1];
        }

        if (preg_match('/(?<![A-Za-z0-9_\\\\])\\\\(Log|Http)::/', $source, $matches)) {
            $offenders[] = basename($file).' calls the root alias \\'.$matches[1];
        }
    }

    expect($offenders)->toBe([]);
});

it('leaves no unfinished work marked in src', function () {
    // A TODO in a published package is a note to nobody: it belongs in an
    // issue, where it can be scheduled.
    $offenders = [];

    foreach (sourceFiles() as $file) {
        if (preg_match('/\b(TODO|FIXME|XXX)\b/', (string) file_get_contents($file), $matches)) {
            $offenders[] = basename($file).' contains a '.$matches[1];
        }
    }

    expect($offenders)->toBe([]);
});

it('names every model with the Model suffix', function () {
    // #28: `*DTO` and `*Data` were used interchangeably with `*Model` and no
    // rule. `*Model` is the v2 name for a shape the API defines; the casts and
    // the few classes that are not spec schemas keep a plain noun.
    $offenders = [];

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(__DIR__.'/../src/Data', FilesystemIterator::SKIP_DOTS)
    );

    foreach ($files as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }

        $name = basename($file->getPathname(), '.php');

        if (str_ends_with($name, 'DTO') || str_ends_with($name, 'Data')) {
            $offenders[] = $name.' uses a retired suffix; name it *Model';
        }
    }

    expect($offenders)->toBe([]);
});

/**
 * #55/#68: services own every endpoint call and DTO conversion; a resource
 * only navigates. These rules pin that split and the names it produced.
 */
it('never lets a resource call an endpoint')
    ->expect('Nikoleesg\NfieldAdmin\Resources')
    ->not->toUse([
        'Nikoleesg\NfieldAdmin\Contracts\Endpoints',
        'Nikoleesg\NfieldAdmin\Endpoints',
        'Nikoleesg\NfieldAdmin\Contracts\Http',
    ]);

it('keeps a resource class only where it navigates', function () {
    // An item with nothing below it is reached as its scoped service
    // (forAddress(), forInterviewer(), ...); a resource that only forwarded
    // calls would be a second name for the same thing.
    $offenders = [];

    foreach (glob(__DIR__.'/../src/Resources/*.php') as $file) {
        $class = 'Nikoleesg\NfieldAdmin\Resources\\'.basename($file, '.php');
        $navigates = false;

        foreach ((new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $returnType = $method->getReturnType();

            if ($returnType instanceof ReflectionNamedType
                && preg_match('/^Nikoleesg\\\\NfieldAdmin\\\\(Services|Resources)\\\\/', $returnType->getName())) {
                $navigates = true;
            }
        }

        if (! $navigates) {
            $offenders[] = class_basename($class).' has nothing to navigate to; make it a scoped service';
        }
    }

    expect($offenders)->toBe([]);
});

it('pairs every collection service with a scoped item service', function () {
    // Services mirror the endpoint naming: XCollectionService pairs with
    // XCollectionEndpoint, XService with XEndpoint and one scoped item.
    $offenders = [];

    foreach (glob(__DIR__.'/../src/Services/*CollectionService.php') as $file) {
        $item = 'Nikoleesg\NfieldAdmin\Services\\'.basename($file, 'CollectionService.php').'Service';

        if (! class_exists($item)) {
            $offenders[] = basename($file, '.php').' has no '.class_basename($item);

            continue;
        }

        $scoped = array_filter(
            class_implements($item),
            fn (string $contract) => str_starts_with($contract, 'Nikoleesg\NfieldAdmin\Contracts\Scoping\\')
        );

        if ($scoped === []) {
            $offenders[] = class_basename($item).' is not scoped to one item';
        }
    }

    expect($offenders)->toBe([]);
});

/**
 * Every method declared on an endpoint contract. The contracts are public
 * (callers bind and mock them), so they follow the same naming as services.
 *
 * @return list<array{class-string, ReflectionMethod}>
 */
function endpointContractMethods(): array
{
    $methods = [];

    foreach (glob(__DIR__.'/../src/Contracts/Endpoints/*.php') as $file) {
        $contract = 'Nikoleesg\NfieldAdmin\Contracts\Endpoints\\'.basename($file, '.php');

        foreach ((new ReflectionClass($contract))->getMethods() as $method) {
            $methods[] = [$contract, $method];
        }
    }

    return $methods;
}

it('uses one verb per operation on endpoint contracts', function () {
    // #75: `destroy()` and `updatePartial()` sat beside `delete()` and
    // `update()` for the same HTTP operations.
    $offenders = [];

    foreach (endpointContractMethods() as [$contract, $method]) {
        if (in_array($method->getName(), ['destroy', 'updatePartial'], true)) {
            $offenders[] = class_basename($contract).'::'.$method->getName().'()';
        }
    }

    expect($offenders)->toBe([]);
});

it('never repeats the class noun in a CRUD method name', function () {
    // #68: the class and the chain already name the resource, so
    // `$survey->getSurvey()` is `$survey->get()`. A verb followed only by
    // words of the class's own name is the redundant form; a verb followed by
    // anything else (`getByClientId`, `createColumns`, `updateStartDate`) is a
    // distinct operation and keeps its noun.
    $singular = fn (string $word) => preg_replace('/(?<=ss)es$|(?<!s)s$/', '', strtolower($word));
    $words = fn (string $name) => array_map($singular, preg_split('/(?=[A-Z])/', $name, -1, PREG_SPLIT_NO_EMPTY));
    $verbs = 'get|list|find|create|update|delete|set|activate|replace|assign|unassign|publish|start';
    $offenders = [];

    foreach ([...publicSdkMethods(), ...endpointContractMethods()] as [$class, $method]) {
        if (! preg_match('/^('.$verbs.')([A-Z]\w*)$/', $method->getName(), $match)) {
            continue;
        }

        // Scope getters and setters (getSurveyId, setInterviewId, ...) come
        // from the ScopedTo* traits and name the scope, not the resource.
        if (in_array(lcfirst(substr($method->getName(), 3)), scopingContracts(), true)) {
            continue;
        }

        $noun = $words(preg_replace('/(Collection)?(Service|Resource|EndpointInterface)$/', '', class_basename($class)));

        if (array_diff($words($match[2]), $noun) === []) {
            $offenders[] = class_basename($class).'::'.$method->getName().'() repeats the class noun; use '.$match[1].'()';
        }
    }

    expect($offenders)->toBe([]);
});

it('documents every manager entry point on the facade', function () {
    // The facade's @method lines are the only thing an IDE sees for
    // NfieldManager::surveys() and friends; eventSubscriptions() went
    // undocumented until #68.
    preg_match_all(
        '/@method static \\\\?([\w\\\\]+) (\w+)\(\)/',
        (string) (new ReflectionClass(NfieldManager::class))->getDocComment(),
        $matches,
        PREG_SET_ORDER
    );

    $documented = [];

    foreach ($matches as [, $type, $name]) {
        $documented[$name] = ltrim($type, '\\');
    }

    $expected = [];

    foreach ((new ReflectionClass(NfieldManagerService::class))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
        if (! $method->isConstructor()) {
            $expected[$method->getName()] = (string) $method->getReturnType();
        }
    }

    ksort($documented);
    ksort($expected);

    expect($documented)->toBe($expected);
});

it('never takes the item id on a service get, update or delete', function () {
    // #72: an operation on one item selects it once, through a forX($id)
    // scope, so get(), update() and delete() never take its id.
    $offenders = [];

    foreach (publicSdkMethods() as [$class, $method]) {
        if (! in_array($method->getName(), ['get', 'update', 'delete'], true)) {
            continue;
        }

        foreach ($method->getParameters() as $parameter) {
            if (preg_match('/(Id|Code|ETag|eTag)$/', $parameter->getName())) {
                $offenders[] = class_basename($class).'::'.$method->getName().'($'.$parameter->getName().') takes the item id; scope it with forX()';
            }
        }
    }

    expect($offenders)->toBe([]);
});
