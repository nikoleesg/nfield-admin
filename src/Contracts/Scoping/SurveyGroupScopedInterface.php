<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service that operates on one survey group. The group ID is an int32.
 */
interface SurveyGroupScopedInterface
{
    public function setSurveyGroupId(int $surveyGroupId): static;

    public function getSurveyGroupId(): int;
}
