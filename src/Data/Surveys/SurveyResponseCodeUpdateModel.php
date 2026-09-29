<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

/**
 * Request body for PATCH /v2/surveys/{surveyId}/responseCodes/{responseCode}.
 *
 * Every field defaults to Optional, so only the fields a caller sets are
 * sent; an explicit null is still sent to clear a field (#48). For system
 * response codes (below 200) the API accepts only description and
 * relocationUrl.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.SurveyResponseCodeModelForPatch,
 * renamed for the *Model suffix every model carries.
 */
final class SurveyResponseCodeUpdateModel extends Data
{
    public function __construct(
        public string|Optional|null $description = new Optional,
        public bool|Optional|null $isDefinite = new Optional,
        public bool|Optional|null $isSelectable = new Optional,
        public bool|Optional|null $allowAppointment = new Optional,
        public string|Optional|null $relocationUrl = new Optional,
    ) {}
}
