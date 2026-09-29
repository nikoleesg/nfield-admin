<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Package;

use Nikoleesg\NfieldAdmin\Data\Surveys\SurveySettingModel;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

/**
 * The content of a published (live or test) package of a survey.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.SurveyPackageV1Model.
 */
final class SurveyPackageV1Model extends Data
{
    /**
     * @param  list<SurveyResponseCodeV1Model>|null  $responseCodes
     * @param  list<SurveyPackageLanguageV1Model>|null  $languages
     * @param  list<SurveyPackageRelocationV1Model>|null  $relocations
     * @param  list<SurveySettingModel>|null  $settings
     * @param  list<SurveyPackageFileV1Model>|null  $mediaFiles
     */
    public function __construct(
        public ?string $surveyName = null,
        public int $eTag = 0,
        public ?string $description = null,
        public ?string $clientName = null,
        public ?string $owner = null,
        public ?string $interviewerInstructionText = null,
        #[DataCollectionOf(SurveyResponseCodeV1Model::class)]
        public ?array $responseCodes = null,
        #[DataCollectionOf(SurveyPackageLanguageV1Model::class)]
        public ?array $languages = null,
        #[DataCollectionOf(SurveyPackageRelocationV1Model::class)]
        public ?array $relocations = null,
        #[DataCollectionOf(SurveySettingModel::class)]
        public ?array $settings = null,
        public ?SurveyPackageFileV1Model $instructionFile = null,
        #[DataCollectionOf(SurveyPackageFileV1Model::class)]
        public ?array $mediaFiles = null,
        public ?string $questionnaireMd5 = null,
    ) {}
}
