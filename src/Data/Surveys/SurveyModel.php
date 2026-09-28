<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Data\Casts\CarbonCast;
use Nikoleesg\NfieldAdmin\Data\Casts\StrictNullCast;
use Nikoleesg\NfieldAdmin\Enums\SurveyStateEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

final class SurveyModel extends Data
{
    public function __construct(
        public ?string $surveyId,
        public string $surveyName,
        #[WithCast(StrictNullCast::class)]
        public ?string $clientName,
        public string $surveyType,
        #[WithCast(StrictNullCast::class)]
        public ?string $description,
        #[WithCast(StrictNullCast::class)]
        public ?string $questionnaireMD5,
        #[WithCast(StrictNullCast::class)]
        public ?string $interviewerInstruction,
        #[WithCast(EnumCast::class, type: SurveyStateEnum::class)]
        public ?SurveyStateEnum $surveyState,
        public ?int $surveyGroupId,
        public ?bool $isBlueprint,
        public ?bool $enableRespondentsGateway,
        #[WithCast(CarbonCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: DATE_ATOM)]
        public ?Carbon $lastStartDate = null
    ) {}
}
