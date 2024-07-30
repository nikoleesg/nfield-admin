<?php


namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
class SurveyResponseCodeForPatch extends Data
{
    public function __construct(
        public string $description,
        public bool $is_definite,
        public bool $is_selectable,
        public bool $allow_appointment,
        public ?string $relocation_url
    ) {
    }

}
