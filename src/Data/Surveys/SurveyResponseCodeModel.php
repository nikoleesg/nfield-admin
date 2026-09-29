<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Data;

/**
 * A survey's response code; also the body of POST .../responseCodes.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.SurveyResponseCodeModel. The API
 * requires isDefinite and allowAppointment not to both be true.
 */
final class SurveyResponseCodeModel extends Data
{
    public function __construct(
        public int $responseCode,
        public ?string $description = null,
        public ?bool $isDefinite = null,
        public ?bool $isSelectable = null,
        public ?bool $allowAppointment = null,
        public ?string $relocationUrl = null,
    ) {}
}
