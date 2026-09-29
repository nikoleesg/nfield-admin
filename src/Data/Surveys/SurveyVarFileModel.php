<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Data;

/**
 * A survey's var file: its content and a suggested file name.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.SurveyVarFileModel.
 */
final class SurveyVarFileModel extends Data
{
    public function __construct(
        public ?string $fileContent = null,
        public ?string $fileName = null,
    ) {}
}
