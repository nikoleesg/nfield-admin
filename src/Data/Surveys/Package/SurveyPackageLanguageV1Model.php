<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Package;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

/**
 * A language and its translations in a published package.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.SurveyPackageLanguageV1Model.
 */
final class SurveyPackageLanguageV1Model extends Data
{
    /**
     * @param  list<SurveyTranslationV1Model>|null  $translations
     */
    public function __construct(
        public int $id = 0,
        public ?string $name = null,
        #[DataCollectionOf(SurveyTranslationV1Model::class)]
        public ?array $translations = null,
    ) {}
}
