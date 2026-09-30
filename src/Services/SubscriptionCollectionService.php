<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SubscriptionCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Events\CreateSubscriptionModel;
use Nikoleesg\NfieldAdmin\Data\Events\SubscriptionModel;

/**
 * The event subscriptions, reached through `NfieldManager::eventSubscriptions()`.
 *
 * Services mirror the endpoint naming: this pairs with
 * SubscriptionCollectionEndpoint, and {@see SubscriptionService} with
 * SubscriptionEndpoint.
 */
class SubscriptionCollectionService
{
    public function __construct(
        protected SubscriptionCollectionEndpointInterface $subscriptionCollectionEndpoint,
    ) {}

    /**
     * @return Collection<int, SubscriptionModel>
     */
    public function list(): Collection
    {
        return SubscriptionModel::collect(
            $this->subscriptionCollectionEndpoint->list(),
            Collection::class
        );
    }

    /**
     * @param  array<string, mixed>|CreateSubscriptionModel  $data
     */
    public function create(array|CreateSubscriptionModel $data): SubscriptionModel
    {
        $payload = CreateSubscriptionModel::from($data)->toArray();

        return SubscriptionModel::from(
            $this->subscriptionCollectionEndpoint->create($payload)
        );
    }

    /**
     * One event subscription, by name.
     */
    public function forSubscription(string $name): SubscriptionService
    {
        return app(SubscriptionService::class)->setSubscriptionName($name);
    }
}
