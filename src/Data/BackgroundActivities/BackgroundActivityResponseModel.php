<?php


namespace Nikoleesg\NfieldAdmin\Data\BackgroundActivities;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;
use Nikoleesg\NfieldAdmin\Enums\ActivityStatusEnum;

#[MapInputName(StudlyCaseMapper::class)]
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
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s.u\Z')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $creationTime,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s.u\Z')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $startTime,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s.u\Z')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $finishTime,
        public ?string $downloadDataUrl
    ) {}
}
