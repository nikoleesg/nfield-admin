<?php

namespace Nikoleesg\NfieldAdmin\Data\BackgroundActivities;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class BackgroundActivityStatus extends Data
{
    public function __construct(
        public string $activityId
    ) {}

}
