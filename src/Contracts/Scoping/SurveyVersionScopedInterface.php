<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service that operates on one published version of a survey.
 *
 * Every version belongs to a survey, so this contract extends
 * {@see SurveyScopedInterface}. The version is the eTag
 * `$survey->versions()->list()` returns, a string.
 */
interface SurveyVersionScopedInterface extends SurveyScopedInterface
{
    public function setSurveyVersion(string $eTag): static;

    public function getSurveyVersion(): string;
}
