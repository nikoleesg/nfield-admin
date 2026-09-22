<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Spatie\LaravelData\Data;

final class SurveysQuotaTargetsResponseModel extends Data
{
    public function __construct(
        public ?string $id,
        public ?int $target,
        /** @var SurveysQuotaTargetsVariableModel[] */
        public ?array $variables,
    ) {}

}
