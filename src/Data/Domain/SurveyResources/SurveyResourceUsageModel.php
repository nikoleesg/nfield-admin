<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Domain\SurveyResources;

use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Data\Casts\CarbonCast;
use Nikoleesg\NfieldAdmin\Enums\SurveyChannelEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyStateEnum;
use Nikoleesg\NfieldAdmin\Resources\SurveyResource;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/**
 * One survey's resource usage: its size and its retention dates.
 *
 * Mirrors NfieldPublicApi.Models.Domain.SurveyResources.SurveyResourceModel,
 * named for what it describes so it cannot be mistaken for the fluent
 * {@see SurveyResource}.
 */
final class SurveyResourceUsageModel extends Data
{
    public function __construct(
        public ?string $surveyId = null,
        public ?string $name = null,
        #[WithCast(EnumCast::class, type: SurveyChannelEnum::class)]
        public ?SurveyChannelEnum $channel = null,
        #[WithCast(CarbonCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: DATE_ATOM)]
        public ?Carbon $creationDate = null,
        public ?string $clientName = null,
        #[WithCast(EnumCast::class, type: SurveyStateEnum::class)]
        public ?SurveyStateEnum $state = null,
        public ?string $owner = null,
        #[WithCast(CarbonCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: DATE_ATOM)]
        public ?Carbon $lastDataDownloadDate = null,
        #[WithCast(CarbonCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: DATE_ATOM)]
        public ?Carbon $lastDataCollectionDate = null,
        #[WithCast(CarbonCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: DATE_ATOM)]
        public ?Carbon $willBeStoppedOn = null,
        #[WithCast(CarbonCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: DATE_ATOM)]
        public ?Carbon $willBeDeletedOn = null,
        public ?int $size = null,
        public bool $isExcludedFromAutomaticCleanup = false,
    ) {}
}
