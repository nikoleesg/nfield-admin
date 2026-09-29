<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\ParentSurvey;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

/**
 * Request body for POST /v2/parentSurveys (an Online parent survey).
 * surveyGroupId is omitted unless set, so the API applies its default group.
 *
 * Mirrors NfieldPublicApi.Models.ParentSurvey.ParentSurveyCreateRequestModel.
 */
final class ParentSurveyCreateRequestModel extends Data
{
    public function __construct(
        public string $surveyName,
        public ?string $clientName = null,
        public ?string $description = null,
        public int|Optional $surveyGroupId = new Optional,
    ) {}
}
