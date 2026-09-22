<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints;

use Spatie\LaravelData\Data;

final class SamplingPointInterviewerAssignmentsModel extends Data
{
    /**
     * @param  array<int, string>|null  $samplingPointIds
     * @param  array<int, string>|null  $interviewerIds
     */
    public function __construct(
        public ?array $samplingPointIds = null,
        public ?array $interviewerIds = null,
    ) {}
}
