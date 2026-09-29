<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Events;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class UpdateSubscriptionModel extends Data
{
    /**
     * @param  array<int, string>|Optional|null  $eventTypes
     */
    public function __construct(
        public string|Optional|null $endpoint = new Optional,
        public array|Optional|null $eventTypes = new Optional,
    ) {}
}
