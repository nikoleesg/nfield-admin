<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Data;

final class SurveyGeneralSettingsUpdateModel extends Data
{
    public function __construct(
        public ?string $name = null,
        public ?string $client = null,
        public ?string $description = null,
        public ?bool $excludeFromAutomaticCleanup = null,
    ) {}
}
