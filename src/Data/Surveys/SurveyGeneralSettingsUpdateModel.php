<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
#[MapName(CamelCaseMapper::class)]
final class SurveyGeneralSettingsUpdateModel extends Data
{
    public function __construct(
        public ?string $name = null,
        public ?string $client = null,
        public ?string $description = null,
        public ?bool $exclude_from_automatic_cleanup = null,
    ) {}
}
