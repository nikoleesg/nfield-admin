<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Package;

use Spatie\LaravelData\Data;

/**
 * A response code as it was when the package was published.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.SurveyResponseCodeV1Model.
 */
final class SurveyResponseCodeV1Model extends Data
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
