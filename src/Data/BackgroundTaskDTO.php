<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data;

use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Enums\BackgroundTaskStatusEnum;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

#[MapInputName(StudlyCaseMapper::class)]
class BackgroundTaskDTO extends Data
{
    public function __construct(
        public string $id,
        public string $parameters,
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d\TH:i:s.uP')]
        public ?Carbon $start_time,
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d\TH:i:s.uP')]
        public ?Carbon $finish_time,
        public ?string $result_url,
        #[WithCast(EnumCast::class)]
        public ?BackgroundTaskStatusEnum $status,
        public ?int $task_type
    ) {}
}
