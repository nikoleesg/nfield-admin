<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\BackgroundActivities;

use Spatie\LaravelData\Data;

class BackgroundActivityStatus extends Data
{
    public function __construct(
        public string $activityId
    ) {}

}
