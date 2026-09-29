<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SubscriptionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\EventSubscriptionScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Events\SubscriptionModel;
use Nikoleesg\NfieldAdmin\Data\Events\UpdateSubscriptionModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToEventSubscription;

/**
 * One event subscription, reached through
 * `NfieldManager::eventSubscriptions()->forSubscription($name)`.
 *
 * Services mirror the endpoint naming: this pairs with SubscriptionEndpoint,
 * and {@see EventSubscriptionCollectionService} with SubscriptionCollectionEndpoint.
 */
class EventSubscriptionService implements EventSubscriptionScopedInterface
{
    use ScopedToEventSubscription;

    public function __construct(
        protected SubscriptionEndpointInterface $subscriptionEndpoint
    ) {}

    public function get(): SubscriptionModel
    {
        return SubscriptionModel::from(
            $this->subscriptionEndpoint->get($this->getSubscriptionName())
        );
    }

    /**
     * @param  array<string, mixed>|UpdateSubscriptionModel  $data
     */
    public function update(array|UpdateSubscriptionModel $data): void
    {
        $payload = UpdateSubscriptionModel::from($data)->toArray();

        $this->subscriptionEndpoint->update($this->getSubscriptionName(), $payload);
    }

    public function delete(): void
    {
        $this->subscriptionEndpoint->delete($this->getSubscriptionName());
    }
}
