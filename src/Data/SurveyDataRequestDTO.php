<?php


namespace Nikoleesg\NfieldAdmin\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class SurveyDataRequestDTO extends Data
{
    public function __construct(
        public ?string $file_name,
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d\TH:i:s.uP')]
        public ?Carbon $start_date,
        #[WithCast(DateTimeInterfaceCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d\TH:i:s.uP')]
        public ?Carbon $end_date,
        public ?string $survey_version,
        public ?bool $include_successful,
        public ?bool $include_screen_out,
        public ?bool $include_dropped_out,
        public ?bool $include_rejected,
        public ?bool $include_Test_data,
        public ?bool $include_closed_answers,
        public ?bool $include_open_answers,
        public ?bool $include_para_data,
        public ?bool $include_captured_media_files,
        public ?bool $include_var_file,
        public ?bool $include_questionnaire_script,
        public ?bool $include_audit_log,
        public ?bool $custom_column_name,
        public ?bool $custom_column_value
    ) {
    }
}
