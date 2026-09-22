<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/**
 * Request body for POST /v2/surveys/{surveyId}/data/download.
 *
 * Mirrors the API schema NfieldPublicApi.Models.Surveys.SurveyDataRequestModel:
 * property names are camelCase on the wire; snake_case input keys are also accepted.
 */
final class SurveyDataRequestModel extends Data
{
    private const DATE_FORMATS = [DATE_ATOM, 'Y-m-d\TH:i:s', 'Y-m-d H:i:s', '!Y-m-d'];

    public function __construct(
        public ?string $fileName = null,
        #[WithCast(DateTimeInterfaceCast::class, format: self::DATE_FORMATS)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: DATE_ATOM)]
        public ?Carbon $startDate = null,
        #[WithCast(DateTimeInterfaceCast::class, format: self::DATE_FORMATS)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: DATE_ATOM)]
        public ?Carbon $endDate = null,
        public ?string $surveyVersion = null,
        public bool $includeSuccessful = true,
        public bool $includeScreenOut = false,
        public bool $includeDroppedOut = false,
        public bool $includeRejected = false,
        public bool $includeTestData = false,
        public bool $includeClosedAnswers = true,
        public bool $includeOpenAnswers = true,
        public bool $includeParaData = true,
        public bool $includeCapturedMediaFiles = false,
        public bool $includeCapturedAudioSilentRecordingFiles = false,
        public bool $includeCapturedAudioQuestionFiles = false,
        public bool $includeCapturedVideoQuestionFiles = false,
        public bool $includeCapturedPhotoQuestionFiles = false,
        public bool $includeVarFile = false,
        public bool $includeQuestionnaireScript = false,
        public bool $includeAuditLog = false,
        public ?string $customColumnName = null,
        public ?string $customColumnValue = null,
    ) {}

    public static function default(): self
    {
        return new self;
    }
}
