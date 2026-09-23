<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Events;

use Spatie\LaravelData\Data;

final class SubscriptionModel extends Data
{
    /**
     * @param  array<int, string>|null  $eventTypes
     */
    public function __construct(
        public ?string $domainId,
        public ?string $name,
        public ?string $webHookUri,
        public ?array $eventTypes
    ) {}
}
