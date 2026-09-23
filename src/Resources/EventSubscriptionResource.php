<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SubscriptionEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Events\SubscriptionModel;
use Nikoleesg\NfieldAdmin\Data\Events\UpdateSubscriptionModel;

class EventSubscriptionResource
{
    protected ?string $subscriptionName = null;

    public function __construct(
        protected SubscriptionEndpointInterface $subscriptionEndpoint
    ) {}

    public function setSubscriptionName(string $name): static
    {
        $this->subscriptionName = $name;

        return $this;
    }

    public function get(): SubscriptionModel
    {
        return SubscriptionModel::from(
            $this->subscriptionEndpoint->get($this->subscriptionName)
        );
    }

    public function update(array|UpdateSubscriptionModel $data): void
    {
        $payload = UpdateSubscriptionModel::from($data)->toArray();

        $this->subscriptionEndpoint->updatePartial($this->subscriptionName, $payload);
    }

    public function delete(): void
    {
        $this->subscriptionEndpoint->destroy($this->subscriptionName);
    }
}
