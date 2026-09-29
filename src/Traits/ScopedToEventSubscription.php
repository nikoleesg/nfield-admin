<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\EventSubscriptionScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements {@see EventSubscriptionScopedInterface}.
 */
trait ScopedToEventSubscription
{
    protected ?string $subscriptionName = null;

    public function setSubscriptionName(string $subscriptionName): static
    {
        $this->subscriptionName = $subscriptionName;

        return $this;
    }

    public function getSubscriptionName(): string
    {
        return $this->subscriptionName ?? throw MissingScopeException::for(static::class, 'subscriptionName');
    }
}
