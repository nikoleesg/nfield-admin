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
 * A survey group.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.SurveyGroupModel.
 */
final class SurveyGroupModel extends Data
{
    public function __construct(
        public int $surveyGroupId,
        public ?string $name = null,
        public ?string $description = null,
        #[WithCast(CarbonCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: DATE_ATOM)]
        public ?Carbon $creationDate = null,
    ) {}
}
