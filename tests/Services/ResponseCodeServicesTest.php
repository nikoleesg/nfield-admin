<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ResponseCodeCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ResponseCodeEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Domain\ResponseCodes\DomainResponseCodeCreateModel;
use Nikoleesg\NfieldAdmin\Data\Domain\ResponseCodes\DomainResponseCodeResponseModel;
use Nikoleesg\NfieldAdmin\Data\Domain\ResponseCodes\DomainResponseCodeUpdateModel;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;
use Nikoleesg\NfieldAdmin\Facades\NfieldManager;
use Nikoleesg\NfieldAdmin\Services\ResponseCodeCollectionService;
use Nikoleesg\NfieldAdmin\Services\ResponseCodeService;

afterEach(function () {
    Mockery::close();
});

function domainResponseCodePayload(array $overrides = []): array
{
    return $overrides + [
        'id' => 210,
        'description' => 'Discarded',
        'url' => 'https://example.com',
        'isDefinite' => true,
        'isSelectable' => false,
        'allowAppointment' => false,
        'channelCapi' => true,
        'channelCati' => false,
        'channelOnline' => true,
    ];
}

it('lists and creates domain response codes', function () {
    $endpoint = Mockery::mock(ResponseCodeCollectionEndpointInterface::class);

    $endpoint->shouldReceive('list')->once()->andReturn([domainResponseCodePayload()]);
    $endpoint->shouldReceive('create')
        ->withArgs(fn (array $payload) => $payload['id'] === 211 && $payload['description'] === 'Callback')
        ->twice()
        ->andReturn(domainResponseCodePayload(['id' => 211, 'description' => 'Callback']));

    $service = new ResponseCodeCollectionService($endpoint);

    $all = $service->list();

    expect($all)->toBeInstanceOf(Collection::class)
        ->and($all->first())->toBeInstanceOf(DomainResponseCodeResponseModel::class)
        ->and($all->first()->id)->toBe(210)
        ->and($all->first()->channelOnline)->toBeTrue()
        ->and($service->create(['id' => 211, 'description' => 'Callback'])->id)->toBe(211)
        ->and($service->create(new DomainResponseCodeCreateModel(id: 211, description: 'Callback'))->description)->toBe('Callback');
});

it('updates only the given fields of the response code it is scoped to', function () {
    $endpoint = Mockery::mock(ResponseCodeEndpointInterface::class);

    $endpoint->shouldReceive('update')
        ->with(210, ['url' => 'https://example.org'])
        ->twice()
        ->andReturn(domainResponseCodePayload(['url' => 'https://example.org']));
    $endpoint->shouldReceive('delete')->with(210)->once();

    $service = (new ResponseCodeService($endpoint))->setResponseCode(210);

    expect($service->update(['url' => 'https://example.org'])->url)->toBe('https://example.org')
        ->and($service->update(new DomainResponseCodeUpdateModel(url: 'https://example.org'))->id)->toBe(210);

    $service->delete();
});

it('scopes the service it hands out to one response code', function () {
    $service = NfieldManager::responseCodes()->forResponseCode(210);

    expect($service)->toBeInstanceOf(ResponseCodeService::class)
        ->and($service->getResponseCode())->toBe(210);
});

it('refuses a response code call before the code is set', function () {
    $service = new ResponseCodeService(Mockery::mock(ResponseCodeEndpointInterface::class));

    expect(fn () => $service->delete())->toThrow(MissingScopeException::class);
});
