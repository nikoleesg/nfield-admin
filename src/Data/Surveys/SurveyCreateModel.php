<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Nikoleesg\NfieldAdmin\Data\Casts\StrictNullCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

final class SurveyCreateModel extends Data
{
    public function __construct(
        public string $surveyName,
        #[WithCast(StrictNullCast::class)]
        public ?string $clientName,
        #[WithCast(StrictNullCast::class)]
        public string $surveyType,
        #[WithCast(StrictNullCast::class)]
        public ?string $description = null,
        #[WithCast(StrictNullCast::class)]
        public ?string $interviewerInstruction = null,
        public ?int $surveyGroupId = null,
        public ?bool $isBlueprint = null,
        public ?bool $enableRespondentsGateway = null
    ) {}
}
