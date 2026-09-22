<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Data;

final class SurveyQuotaFrameRequestModel extends Data
{
    public function __construct(
        public ?int $target,
        /** @var SurveysQuotaVariableDefinitionModel[] */
        public ?array $variableDefinitions,
        /** @var SurveysQuotaFrameVariableModel[] */
        public ?array $frameVariables
    ) {}

}
