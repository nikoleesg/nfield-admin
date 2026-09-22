<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Quota;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

final class QuotaFrameVersionModel extends Data
{
    public function __construct(
        public ?string $id = null,
        public ?string $eTag = null,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?Carbon $publishedDate = null,
    ) {}
}
