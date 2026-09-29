<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Package;

use Spatie\LaravelData\Data;

/**
 * A translated text item in a published package.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.SurveyTranslationV1Model.
 */
final class SurveyTranslationV1Model extends Data
{
    public function __construct(
        public string $name,
        public ?string $text = null,
    ) {}
}
