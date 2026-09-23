<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SubscriptionCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SubscriptionEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Events\CreateSubscriptionModel;
use Nikoleesg\NfieldAdmin\Data\Events\SubscriptionModel;
use Nikoleesg\NfieldAdmin\Resources\EventSubscriptionResource;

class EventSubscriptionService
{
    public function __construct(
        protected SubscriptionCollectionEndpointInterface $subscriptionCollectionEndpoint,
        protected SubscriptionEndpointInterface $subscriptionEndpoint
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

    public function create(array|CreateSubscriptionModel $data): SubscriptionModel
    {
        $payload = CreateSubscriptionModel::from($data)->toArray();

        return SubscriptionModel::from(
            $this->subscriptionCollectionEndpoint->create($payload)
        );
    }

    public function forSubscription(string $name): EventSubscriptionResource
    {
        return (new EventSubscriptionResource($this->subscriptionEndpoint))
            ->setSubscriptionName($name);
    }
}
