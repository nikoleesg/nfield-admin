<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Nikoleesg\NfieldAdmin\Data\Quota\QuotaLevel;
use Spatie\LaravelData\Data;

class SurveyCountsModel extends Data
{
    public function __construct(
        public ?string $surveyId,
        public ?int $successfulCount,
        public ?int $screenedOutCount,
        public ?int $droppedOutCount,
        public ?int $rejectedCount,
        public ?QuotaLevel $quotaCounts,
        public int $activeLiveCount,
        public int $activeTestCount
    ) {}

}
