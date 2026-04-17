<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
final class SurveyGeneralSettingsModel extends Data
{
    public function __construct(
        public ?string $name,
        public ?string $client,
        public ?string $description,
        public ?bool $excludeFromAutomaticCleanup,
        public ?SurveyOwnerModel $owner,
    ) {}
}
