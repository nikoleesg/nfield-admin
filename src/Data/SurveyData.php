<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data;

use Illuminate\Support\Str;
use Nikoleesg\NfieldAdmin\Enums\ChannelEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyStateEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyTypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Concerns\WithDeprecatedCollectionMethod;
use Spatie\LaravelData\Data;

class SurveyData extends Data
{
    use WithDeprecatedCollectionMethod;

    public function __construct(
        public ?string $clientName,
        #[WithCast(EnumCast::class)]
        public SurveyTypeEnum $surveyType,
        public ?string $description,
        public ?string $questionnaireMd5,
        public ?string $interviewerInstruction,
        #[WithCast(EnumCast::class)]
        public ?SurveyStateEnum $surveyState,
        public ?int $surveyGroupId,
        public ?bool $isBlueprint,
        public ?string $surveyId,
        public string $surveyName
    ) {}

    public static function fromBasic(string $surveyName, ChannelEnum|string $channel = ChannelEnum::Online): self
    {
        if (is_string($channel)) {
            $channel = ChannelEnum::tryFrom(Str::studly($channel)) ?? ChannelEnum::Online;
        }

        $surveyType = match ($channel) {
            ChannelEnum::Online => SurveyTypeEnum::Online,
            ChannelEnum::CAPI => SurveyTypeEnum::FreeIntercept,
        };

        return new self(
            clientName: null,
            surveyType: $surveyType,
            description: null,
            questionnaireMd5: null,
            interviewerInstruction: null,
            surveyState: SurveyStateEnum::UnderConstruction,
            surveyGroupId: 1,
            isBlueprint: false,
            surveyId: null,
            surveyName: $surveyName
        );
    }
}
