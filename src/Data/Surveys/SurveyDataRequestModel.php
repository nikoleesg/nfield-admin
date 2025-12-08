<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

#[MapInputName(SnakeCaseMapper::class)]
final class SurveyDataRequestModel extends Data
{
    public function __construct(
        public string $fileName,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public Carbon $startDate,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s')]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $endDate,
        public ?string $surveyVersion,
        public ?bool $includeSuccessful = true,
        public ?bool $includeScreenOut = true,
        public ?bool $includeDroppedOut = true,
        public ?bool $includeRejected = false,
        public ?bool $includeTestData = false,
        public ?bool $includeClosedAnswers = true,
        public ?bool $includeOpenAnswers = true,
        public ?bool $includeParaData = true,
        public ?bool $includeCapturedMediaFiles = false,
        public ?bool $includeCapturedAudioSilentRecordingFiles = false,
        public ?bool $includeCapturedAudioQuestionFiles = false,
        public ?bool $includeCapturedVideoQuestionFiles = false,
        public ?bool $includeCapturedPhotoQuestionFiles = false,
        public ?bool $includeVarFile = false,
        public ?bool $includeQuestionnaireScript = false,
        public ?bool $includeAuditLog = false,
        public ?string $customColumnName,
        public ?string $customColumnValue
    ) {}

    public static function default(): self
    {
        return new self(
            fileName: null,
            startDate: null,
            endDate: null,
            surveyVersion: null,
            includeSuccessful: true,
            includeScreenOut: false,
            includeDroppedOut: false,
            includeRejected: false,
            includeTestData: false,
            includeClosedAnswers: true,
            includeOpenAnswers: true,
            includeParaData: true,
            includeCapturedMediaFiles: true,
            includeCapturedAudioSilentRecordingFiles: false,
            includeCapturedAudioQuestionFiles: false,
            includeCapturedVideoQuestionFiles: false,
            includeCapturedPhotoQuestionFiles: false,
            includeVarFile: false,
            includeQuestionnaireScript: false,
            includeAuditLog: false,
            customColumnName: null,
            customColumnValue: null,
        );
    }
}
