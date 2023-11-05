<?php

namespace Nikoleesg\NfieldAdmin\Data;

use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;
use Spatie\LaravelData\Casts\EnumCast;
use Nikoleesg\NfieldAdmin\Enums\ChannelEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyTypeEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyStateEnum;

#[MapInputName(StudlyCaseMapper::class)]
class SurveyData extends Data
{
    public function __construct(
        public ?string $client_name,
        #[WithCast(EnumCast::class)]
        public SurveyTypeEnum $survey_type,
        public ?string $description,
        public ?string $questionnaire_md5,
        public ?string $interviewer_instruction,
        #[WithCast(EnumCast::class)]
        public ?SurveyStateEnum $survey_state,
        public ?int $survey_group_id,
        public ?bool $is_blueprint,
        public ?string $survey_id,
        public string $survey_name
    ) {
    }

    public static function fromBasic(string $surveyName, ChannelEnum|string $channel = ChannelEnum::Online): self
    {
        if (is_string($channel)) {
            $channel = ChannelEnum::tryFrom(Str::studly($channel)) ?? ChannelEnum::Online;
        }

        $surveyType = match ($channel) {
            ChannelEnum::Online => SurveyTypeEnum::Online,
            ChannelEnum::CAPI => SurveyTypeEnum::FreeIntercept,
        };

        return new self(
            client_name: null,
            survey_type: $surveyType,
            description: null,
            questionnaire_md5: null,
            interviewer_instruction: null,
            survey_state: SurveyStateEnum::UnderConstruction,
            survey_group_id: 1,
            is_blueprint: false,
            survey_id: null,
            survey_name: $surveyName
        );
    }

}
