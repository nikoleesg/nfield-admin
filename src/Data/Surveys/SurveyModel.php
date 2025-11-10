<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Nikoleesg\NfieldAdmin\Data\Casts\StrictNullCast;
use Nikoleesg\NfieldAdmin\Enums\SurveyStateEnum;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class SurveyModel extends Data
{
    public function __construct(
        public ?string $SurveyId,
        public string $SurveyName,
        #[WithCast(StrictNullCast::class)]
        public ?string $ClientName,
        #[WithCast(StrictNullCast::class)]
        public string $SurveyType,
        #[WithCast(StrictNullCast::class)]
        public ?string $Description,
        #[WithCast(StrictNullCast::class)]
        #[MapOutputName('questionnaire_md5')]
        public ?string $QuestionnaireMD5,
        #[WithCast(StrictNullCast::class)]
        public ?string $InterviewerInstruction,
        #[WithCast(EnumCast::class, type: SurveyStateEnum::class)]
        public ?SurveyStateEnum $SurveyState,
        public ?int $SurveyGroupId,
        public ?bool $IsBlueprint,
        public ?bool $EnableRespondentsGateway,
        public ?string $LastStartDate
    ) {}

}
