<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service or resource that operates inside a single survey.
 *
 * #41: the scope used to be injected by the container matching the literal
 * constructor parameter name `$surveyId`, which meant a rename silently
 * stopped the injection. The scope is now an explicit contract.
 */
interface SurveyScopedInterface
{
    public function setSurveyId(string $surveyId): static;

    public function getSurveyId(): string;
}
