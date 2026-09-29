<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service or resource that operates on a single event subscription.
 *
 * Subscriptions are addressed by name, not by an ID.
 */
interface EventSubscriptionScopedInterface
{
    public function setSubscriptionName(string $subscriptionName): static;

    public function getSubscriptionName(): string;
}
