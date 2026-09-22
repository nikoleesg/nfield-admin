<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data;

use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Enums\ActivityStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class BackgroundActivityDTO extends Data
{
    //    #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d\TH:i:s.uP')]

    public function __construct(
        public string $activityId,
        public ?string $name,
        public ?string $userId,
        #[WithCast(EnumCast::class)]
        public ?ActivityStatusEnum $status,
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $creationTime,
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $startTime,
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $finishTime,
        public ?string $downloadDataUrl
    ) {}

    public static function fromInitialised(array $activity): self
    {
        if (count($activity) == 1) {
            return new self($activity['activityId'], null, null, null, null, null, null, null);
        }

        return new self(
            $activity['activityId'],
            $activity['name'],
            $activity['userId'],
            ActivityStatusEnum::tryFrom($activity['status']),
            ! is_null($activity['creationTime']) ? Carbon::parse($activity['creationTime']) : null,
            ! is_null($activity['startTime']) ? Carbon::parse($activity['startTime']) : null,
            ! is_null($activity['finishTime']) ? Carbon::parse($activity['finishTime']) : null,
            $activity['downloadDataUrl']
        );
    }
}
