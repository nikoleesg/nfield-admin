<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Nikoleesg\NfieldAdmin\Data\Quota\QuotaLevel;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\StudlyCaseMapper;

#[MapInputName(StudlyCaseMapper::class)]
#[MapOutputName(StudlyCaseMapper::class)]
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
