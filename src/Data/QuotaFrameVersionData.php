<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class QuotaFrameVersionData extends Data
{
    public function __construct(
        public string $id,
        public string $eTag,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?Carbon $publishedDate,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            $data['id'],
            $data['eTag'],
            ! is_null($data['publishedDate']) ? Carbon::parse($data['publishedDate']) : null
        );
    }
}
