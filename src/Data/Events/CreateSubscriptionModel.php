<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Events;

use Spatie\LaravelData\Data;

final class CreateSubscriptionModel extends Data
{
    /**
     * @param  array<int, string>|null  $eventTypes
     */
    public function __construct(
        public ?string $eventSubscriptionName,
        public ?string $endpoint,
        public ?array $eventTypes
    ) {}
}
