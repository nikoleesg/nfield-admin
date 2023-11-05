<?php

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;
use Spatie\LaravelData\Casts\EnumCast;
use Nikoleesg\NfieldAdmin\Enums\SurveyStateEnum;

#[MapInputName(StudlyCaseMapper::class)]
class SurveyData extends Data
{
    public function __construct(
        public ?string $client_name,
        public string $survey_type,
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
}
