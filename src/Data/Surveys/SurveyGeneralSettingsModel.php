<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Data;

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
