<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Enums\InterviewQualityEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

class ManagerInterviewDetailsModel extends Data
{
    public function __construct(
        public ?string $interviewId,
        public ?string $surveyId,
        #[WithCast(EnumCast::class)]
        public ?InterviewQualityEnum $interviewQuality,
        public ?string $clientInterviewerId,
        public ?string $interviewer,
        public ?string $samplingPointName,
        public ?string $samplingPointId,
        public ?string $officeId,
        public ?string $officeName,
        public ?int $interviewDuration,
        public ?int $averageInterviewDuration,
        public ?int $interviewMedianQuestionDuration,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?Carbon $interviewStartTime,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?Carbon $interviewEndTime,
        public int $responseCode,
        public int $interviewResult,
        public bool $isScreenedOut,
    ) {}
}
