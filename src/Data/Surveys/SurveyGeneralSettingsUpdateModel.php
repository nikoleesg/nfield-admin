<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class SurveyGeneralSettingsUpdateModel extends Data
{
    public function __construct(
        public string|Optional|null $name = new Optional,
        public string|Optional|null $client = new Optional,
        public string|Optional|null $description = new Optional,
        public bool|Optional|null $excludeFromAutomaticCleanup = new Optional,
        public string|Optional|null $ownerId = new Optional,
    ) {}
}
