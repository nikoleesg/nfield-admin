<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\BackgroundActivities;

use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Data\Casts\CarbonCast;
use Nikoleesg\NfieldAdmin\Enums\ActivityStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class BackgroundActivityResponseModel extends Data
{
    public function __construct(
        public string $id,
        public int $activityType,
        public string $activityTypeName,
        public string $activityName,
        #[WithCast(EnumCast::class)]
        public ActivityStatusEnum $status,
        public string $statusName,
        public string $userId,
        #[WithCast(CarbonCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: DATE_ATOM)]
        public ?Carbon $creationTime,
        #[WithCast(CarbonCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: DATE_ATOM)]
        public ?Carbon $startTime,
        #[WithCast(CarbonCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: DATE_ATOM)]
        public ?Carbon $finishTime,
        public ?string $downloadDataUrl
    ) {}
}
