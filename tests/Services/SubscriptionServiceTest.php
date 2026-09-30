<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Tests\Services;

use Illuminate\Support\Collection;
use Mockery;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SubscriptionCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SubscriptionEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Events\SubscriptionModel;
use Nikoleesg\NfieldAdmin\Services\SubscriptionCollectionService;
use Nikoleesg\NfieldAdmin\Services\SubscriptionService;

it('lists subscriptions as models', function () {
    $collection = Mockery::mock(SubscriptionCollectionEndpointInterface::class);

    $collection->shouldReceive('list')->once()->andReturn([
        [
            'domainId' => 'domain-1',
            'name' => 'sub-1',
            'webHookUri' => 'https://example.com/webhook',
            'eventTypes' => ['Event.Created'],
        ],
    ]);

    $service = new SubscriptionCollectionService($collection);

    $all = $service->list();

    expect($all)->toBeInstanceOf(Collection::class)
        ->and($all->first())->toBeInstanceOf(SubscriptionModel::class)
        ->and($all->first()->name)->toBe('sub-1');
});

it('creates a subscription and returns the model', function () {
    $collection = Mockery::mock(SubscriptionCollectionEndpointInterface::class);

    $collection->shouldReceive('create')
        ->with(['eventSubscriptionName' => 'sub-1', 'endpoint' => 'https://example.com/webhook', 'eventTypes' => ['Event.Created']])
        ->once()
        ->andReturn([
            'domainId' => 'domain-1',
            'name' => 'sub-1',
            'webHookUri' => 'https://example.com/webhook',
            'eventTypes' => ['Event.Created'],
        ]);

    $service = new SubscriptionCollectionService($collection);

    $created = $service->create([
        'eventSubscriptionName' => 'sub-1',
        'endpoint' => 'https://example.com/webhook',
        'eventTypes' => ['Event.Created'],
    ]);

    expect($created)->toBeInstanceOf(SubscriptionModel::class)
        ->and($created->name)->toBe('sub-1')
        ->and($created->webHookUri)->toBe('https://example.com/webhook');
});

it('returns a scoped service for an event subscription', function () {
    $service = new SubscriptionCollectionService(Mockery::mock(SubscriptionCollectionEndpointInterface::class));

    $resource = $service->forSubscription('sub-1');
    expect($resource)->toBeInstanceOf(SubscriptionService::class);
});

it('gets a subscription via its service', function () {
    $endpoint = Mockery::mock(SubscriptionEndpointInterface::class);
    $endpoint->shouldReceive('get')->with('sub-1')->once()->andReturn([
        'domainId' => 'domain-1',
        'name' => 'sub-1',
        'webHookUri' => 'https://example.com/webhook',
        'eventTypes' => ['Event.Created'],
    ]);

    $resource = (new SubscriptionService($endpoint))->setSubscriptionName('sub-1');
    $model = $resource->get();

    expect($model)->toBeInstanceOf(SubscriptionModel::class)
        ->and($model->name)->toBe('sub-1');
});

it('updates a subscription via its service', function () {
    $endpoint = Mockery::mock(SubscriptionEndpointInterface::class);
    $endpoint->shouldReceive('update')
        ->with('sub-1', ['endpoint' => 'https://example.com/new', 'eventTypes' => ['Event.Updated']])
        ->once();

    $resource = (new SubscriptionService($endpoint))->setSubscriptionName('sub-1');
    $resource->update([
        'endpoint' => 'https://example.com/new',
        'eventTypes' => ['Event.Updated'],
    ]);
});

it('partially updates a subscription with only changed fields', function () {
    $endpoint = Mockery::mock(SubscriptionEndpointInterface::class);
    $endpoint->shouldReceive('update')
        ->with('sub-1', ['endpoint' => 'https://example.com/new'])
        ->once();

    $resource = (new SubscriptionService($endpoint))->setSubscriptionName('sub-1');
    $resource->update([
        'endpoint' => 'https://example.com/new',
    ]);
});

it('deletes a subscription via its service', function () {
    $endpoint = Mockery::mock(SubscriptionEndpointInterface::class);
    $endpoint->shouldReceive('delete')->with('sub-1')->once();

    $resource = (new SubscriptionService($endpoint))->setSubscriptionName('sub-1');
    $resource->delete();
});
