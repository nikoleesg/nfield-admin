<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Data;

/**
 * A survey's ODIN script, with any warnings the API raised parsing it.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.SurveyGetScriptModel.
 */
final class SurveyGetScriptModel extends Data
{
    /**
     * @param  list<string>|null  $warningMessages
     */
    public function __construct(
        public ?string $script = null,
        public ?string $fileName = null,
        public ?array $warningMessages = null,
    ) {}
}
