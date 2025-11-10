<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
final class SurveysQuotaFrameResponseModel extends Data
{
    public function __construct(
        public ?int $target,
        /** @var SurveysQuotaVariableDefinitionModel[] */
        public ?array $variableDefinitions,
        /** @var SurveysQuotaFrameVariableModel[] */
        public ?array $frameVariables,
        public ?string $id,
        public ?int $quotaETag
    ) {}

}
