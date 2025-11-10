<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

final class SurveyDataRequestModel extends Data
{
    public function __construct(
        public ?string $fileName,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $startDate,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public ?Carbon $endDate,
        public ?string $surveyVersion,
        public bool $includeSuccessful,
        public bool $includeScreenOut,
        public bool $includeDroppedOut,
        public bool $includeRejected,
        public bool $includeTestData,
        public bool $includeClosedAnswers,
        public bool $includeOpenAnswers,
        public bool $includeParaData,
        public bool $includeCapturedMediaFiles,
        public bool $includeCapturedAudioSilentRecordingFiles,
        public bool $includeCapturedAudioQuestionFiles,
        public bool $includeCapturedVideoQuestionFiles,
        public bool $includeCapturedPhotoQuestionFiles,
        public bool $includeVarFile,
        public bool $includeQuestionnaireScript,
        public bool $includeAuditLog,
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
