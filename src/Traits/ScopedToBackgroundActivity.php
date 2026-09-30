<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\BackgroundActivityScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements {@see BackgroundActivityScopedInterface}.
 */
trait ScopedToBackgroundActivity
{
    protected ?string $activityId = null;

    public function setActivityId(string $activityId): static
    {
        $this->activityId = $activityId;

        return $this;
    }

    public function getActivityId(): string
    {
        return $this->activityId ?? throw MissingScopeException::for(static::class, 'activityId');
    }
}
