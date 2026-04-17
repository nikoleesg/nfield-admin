<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
#[MapName(StudlyCaseMapper::class)]
final class SurveyGeneralSettingsUpdateModel extends Data
{
    public function __construct(
        public ?string $name = null,
        public ?string $client = null,
        public ?string $description = null,
        public ?bool $exclude_from_automatic_cleanup = null,
    ) {}
}
