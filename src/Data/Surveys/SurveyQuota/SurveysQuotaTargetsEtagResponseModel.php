<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

final class SurveysQuotaTargetsEtagResponseModel extends Data
{
    public function __construct(
        public ?string $id,
        public ?int $target,
        /** @var Collection<int, SurveysQuotaTargetsEtagVariableModel> */
        public Collection $variables,
        public int $successful
    ) {}

}
