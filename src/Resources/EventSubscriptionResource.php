<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SubscriptionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\EventSubscriptionScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Events\SubscriptionModel;
use Nikoleesg\NfieldAdmin\Data\Events\UpdateSubscriptionModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToEventSubscription;

class EventSubscriptionResource implements EventSubscriptionScopedInterface
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

        $this->subscriptionEndpoint->updatePartial($this->getSubscriptionName(), $payload);
    }

    public function delete(): void
    {
        $this->subscriptionEndpoint->destroy($this->getSubscriptionName());
    }
}
