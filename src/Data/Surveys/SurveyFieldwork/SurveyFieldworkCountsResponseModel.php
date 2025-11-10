<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyFieldwork;

use Nikoleesg\NfieldAdmin\Data\Surveys\ResponseCodeCount;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

final class SurveyFieldworkCountsResponseModel extends Data
{
    public function __construct(
        public ?string $surveyId,
        public int $successful,
        public int $successfulLast24Hours,
        public int $screenedOut,
        public int $droppedOut,
        public int $rejected,
        public int $successfulDeleted,
        public int $screenedOutDeleted,
        public int $droppedOutDeleted,
        public int $rejectedDeleted,
        public int $activeInterviews,
        #[DataCollectionOf(ResponseCodeCount::class)]
        public ?array $screenedOutOverview,
    ) {}
}
