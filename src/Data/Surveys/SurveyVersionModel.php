<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Data\Casts\CarbonCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/**
 * One published version of a survey, identified by its eTag, with its
 * interview counts.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.SurveyVersionModel.
 */
final class SurveyVersionModel extends Data
{
    public function __construct(
        public ?string $eTag = null,
        #[WithCast(CarbonCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: DATE_ATOM)]
        public ?Carbon $publishDateUtc = null,
        public int $nrOfSuccessfuls = 0,
        public int $nrOfDroppedOuts = 0,
        public int $nrOfScreenedOuts = 0,
    ) {}
}
