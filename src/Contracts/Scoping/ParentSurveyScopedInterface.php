<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service or resource that operates on one parent survey (a survey that groups waves).
 */
interface ParentSurveyScopedInterface
{
    public function setParentSurveyId(string $parentSurveyId): static;

    public function getParentSurveyId(): string;
}
