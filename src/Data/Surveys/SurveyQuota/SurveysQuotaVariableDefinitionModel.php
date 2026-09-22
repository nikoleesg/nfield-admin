<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Data;

final class SurveysQuotaVariableDefinitionModel extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $odinVariableName,
        public ?bool $isSelectionOptional,
        public bool $isMulti,
        public bool $isTargetable,
        /** @var SurveysQuotaLevelDefinitionModel[] */
        public ?array $levels
    ) {}
}
