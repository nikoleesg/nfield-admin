<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Tests\Services;

use Illuminate\Support\Collection;
use Mockery;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SubscriptionCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SubscriptionEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Events\SubscriptionModel;
use Nikoleesg\NfieldAdmin\Resources\EventSubscriptionResource;
use Nikoleesg\NfieldAdmin\Services\EventSubscriptionService;

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

    $service = new EventSubscriptionService(
        $collection,
        Mockery::mock(SubscriptionEndpointInterface::class)
    );

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

    $service = new EventSubscriptionService(
        $collection,
        Mockery::mock(SubscriptionEndpointInterface::class)
    );

    $created = $service->create([
        'eventSubscriptionName' => 'sub-1',
        'endpoint' => 'https://example.com/webhook',
        'eventTypes' => ['Event.Created'],
    ]);

    expect($created)->toBeInstanceOf(SubscriptionModel::class)
        ->and($created->name)->toBe('sub-1')
        ->and($created->webHookUri)->toBe('https://example.com/webhook');
});

it('returns scoped resources for an event subscription', function () {
    $service = new EventSubscriptionService(
        Mockery::mock(SubscriptionCollectionEndpointInterface::class),
        Mockery::mock(SubscriptionEndpointInterface::class)
    );

    $resource = $service->forSubscription('sub-1');
    expect($resource)->toBeInstanceOf(EventSubscriptionResource::class);
});

it('gets a subscription via its resource', function () {
    $endpoint = Mockery::mock(SubscriptionEndpointInterface::class);
    $endpoint->shouldReceive('get')->with('sub-1')->once()->andReturn([
        'domainId' => 'domain-1',
        'name' => 'sub-1',
        'webHookUri' => 'https://example.com/webhook',
        'eventTypes' => ['Event.Created'],
    ]);

    $resource = (new EventSubscriptionResource($endpoint))->setSubscriptionName('sub-1');
    $model = $resource->get();

    expect($model)->toBeInstanceOf(SubscriptionModel::class)
        ->and($model->name)->toBe('sub-1');
});

it('updates a subscription via its resource', function () {
    $endpoint = Mockery::mock(SubscriptionEndpointInterface::class);
    $endpoint->shouldReceive('updatePartial')
        ->with('sub-1', ['endpoint' => 'https://example.com/new', 'eventTypes' => ['Event.Updated']])
        ->once();

    $resource = (new EventSubscriptionResource($endpoint))->setSubscriptionName('sub-1');
    $resource->update([
        'endpoint' => 'https://example.com/new',
        'eventTypes' => ['Event.Updated'],
    ]);
});

it('deletes a subscription via its resource', function () {
    $endpoint = Mockery::mock(SubscriptionEndpointInterface::class);
    $endpoint->shouldReceive('destroy')->with('sub-1')->once();

    $resource = (new EventSubscriptionResource($endpoint))->setSubscriptionName('sub-1');
    $resource->delete();
});
