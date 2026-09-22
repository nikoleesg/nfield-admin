<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\SurveyQuotaFrame;

use Spatie\LaravelData\Data;

class SurveyQuotaLevelDefinitionData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
    ) {}
}
