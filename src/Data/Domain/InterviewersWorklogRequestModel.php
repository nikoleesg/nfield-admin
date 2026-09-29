<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Domain;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/**
 * Request body for POST /v2/interviewersWorklog.
 *
 * Mirrors NfieldPublicApi.Models.Domain.InterviewersWorklogRequestModel. The API
 * expects both dates in UTC, so they are converted to UTC on the way out
 * whatever timezone they were given in.
 */
final class InterviewersWorklogRequestModel extends Data
{
    private const DATE_FORMATS = [DATE_ATOM, 'Y-m-d\TH:i:s', 'Y-m-d H:i:s', '!Y-m-d'];

    public function __construct(
        #[WithCast(DateTimeInterfaceCast::class, format: self::DATE_FORMATS)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: DATE_ATOM, setTimeZone: 'UTC')]
        public ?Carbon $from = null,
        #[WithCast(DateTimeInterfaceCast::class, format: self::DATE_FORMATS)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: DATE_ATOM, setTimeZone: 'UTC')]
        public ?Carbon $to = null,
    ) {}
}
