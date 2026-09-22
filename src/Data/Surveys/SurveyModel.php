<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Nikoleesg\NfieldAdmin\Data\Casts\StrictNullCast;
use Nikoleesg\NfieldAdmin\Enums\SurveyStateEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

final class SurveyModel extends Data
{
    public function __construct(
        public ?string $surveyId,
        public string $surveyName,
        #[WithCast(StrictNullCast::class)]
        public ?string $clientName,
        #[WithCast(StrictNullCast::class)]
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
        public ?string $lastStartDate
    ) {}
}
