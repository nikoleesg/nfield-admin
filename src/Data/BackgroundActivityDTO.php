<?php


namespace Nikoleesg\NfieldAdmin\Data;

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
class BackgroundActivityDTO extends Data
{
//    #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d\TH:i:s.uP')]

    public function __construct(
        public string $activity_id,
        public ?string $name,
        public ?string $user_id,
        #[WithCast(EnumCast::class)]
        public ?ActivityStatusEnum $status,
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $creation_time,
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $start_time,
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $finish_time,
        public ?string $download_data_url
    ) {
    }

    public static function fromInitialised(array $activity): self
    {
        if (count($activity) == 1) {
            return new self($activity['ActivityId'], null, null, null, null, null, null, null);
        }

        return new self(
            $activity['ActivityId'],
            $activity['Name'],
            $activity['UserId'],
            ActivityStatusEnum::tryFrom($activity['Status']),
            !is_null($activity['CreationTime']) ? Carbon::parse($activity['CreationTime']) : null,
            !is_null($activity['StartTime']) ? Carbon::parse($activity['StartTime']) : null,
            !is_null($activity['FinishTime']) ? Carbon::parse($activity['FinishTime']): null,
            $activity['DownloadDataUrl']
        );
    }
}
