<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Data;

final class SurveysQuotaLevelDefinitionModel extends Data
{
    public function __construct(
        public string $id,
        public ?string $name,
    ) {}

}
