<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service that operates on one background activity. The id is a string.
 */
interface BackgroundActivityScopedInterface
{
    public function setActivityId(string $activityId): static;

    public function getActivityId(): string;
}
