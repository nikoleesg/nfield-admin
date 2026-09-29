<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\RequestConfigurationCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\RequestConfigurationEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Requests\RequestConfigurationModel;
use Nikoleesg\NfieldAdmin\Enums\RequestHttpMethodEnum;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;
use Nikoleesg\NfieldAdmin\Facades\NfieldManager;
use Nikoleesg\NfieldAdmin\Services\RequestConfigurationCollectionService;
use Nikoleesg\NfieldAdmin\Services\RequestConfigurationService;

afterEach(function () {
    Mockery::close();
});

function requestConfigurationPayload(array $overrides = []): array
{
    return $overrides + [
        'id' => 12,
        'name' => 'crm',
        'description' => 'CRM hook',
        'uri' => 'https://crm.example.com/hook',
        'payloadTemplate' => null,
        'requestHttpMethod' => 2,
        'helpUri' => null,
        'headers' => [],
    ];
}

it('lists, finds by name and creates request configurations', function () {
    $endpoint = Mockery::mock(RequestConfigurationCollectionEndpointInterface::class);

    $endpoint->shouldReceive('list')->withNoArgs()->once()->andReturn([requestConfigurationPayload()]);
    $endpoint->shouldReceive('list')->with(['name' => 'crm'])->once()->andReturn([requestConfigurationPayload()]);
    $endpoint->shouldReceive('list')->with(['name' => 'missing'])->once()->andReturn([]);
    $endpoint->shouldReceive('create')
        // Unset API-assigned fields stay out of the body.
        ->with(['name' => 'crm', 'uri' => 'https://crm.example.com/hook', 'description' => null, 'payloadTemplate' => null, 'requestHttpMethod' => 2, 'helpUri' => null, 'headers' => null])
        ->once();

    $service = new RequestConfigurationCollectionService($endpoint);

    $all = $service->list();

    expect($all)->toBeInstanceOf(Collection::class)
        ->and($all->first())->toBeInstanceOf(RequestConfigurationModel::class)
        ->and($service->findByName('crm')->requestHttpMethod)->toBe(RequestHttpMethodEnum::Post)
        ->and($service->findByName('missing'))->toBeNull();

    $service->create(['name' => 'crm', 'uri' => 'https://crm.example.com/hook', 'requestHttpMethod' => RequestHttpMethodEnum::Post]);
});

it('updates the configuration it is scoped to with the id taken from the scope', function () {
    // The API takes the id in the body of a PUT to /v2/requests; a caller's
    // id is overwritten, so a scoped service cannot update another record.
    $collection = Mockery::mock(RequestConfigurationCollectionEndpointInterface::class);
    $item = Mockery::mock(RequestConfigurationEndpointInterface::class);

    $collection->shouldReceive('update')
        ->withArgs(fn (array $payload) => $payload['id'] === 12 && $payload['name'] === 'crm2')
        ->twice()
        ->andReturn(requestConfigurationPayload(['name' => 'crm2', 'timeout' => 30]));
    $item->shouldReceive('get')->with(12)->once()->andReturn(requestConfigurationPayload());
    $item->shouldReceive('delete')->with(12)->once();

    $service = (new RequestConfigurationService($item, $collection))->setRequestConfigurationId(12);

    $updated = $service->update(['name' => 'crm2', 'uri' => 'https://crm.example.com/hook', 'id' => 99]);

    expect($updated->name)->toBe('crm2')
        // The read-only timeout is read but never sent back.
        ->and($updated->timeout)->toBe(30)
        ->and($updated->toArray())->not->toHaveKey('timeout')
        ->and($service->update(new RequestConfigurationModel('crm2', 'https://crm.example.com/hook'))->name)->toBe('crm2')
        ->and($service->get()->id)->toBe(12);

    $service->delete();
});

it('scopes the service it hands out to one configuration', function () {
    $config = NfieldManager::requestConfigurations()->forRequestConfiguration(12);

    expect($config)->toBeInstanceOf(RequestConfigurationService::class)
        ->and($config->getRequestConfigurationId())->toBe(12);
});

it('refuses a configuration call before the id is set', function () {
    $service = new RequestConfigurationService(
        Mockery::mock(RequestConfigurationEndpointInterface::class),
        Mockery::mock(RequestConfigurationCollectionEndpointInterface::class),
    );

    expect(fn () => $service->get())->toThrow(MissingScopeException::class);
});
