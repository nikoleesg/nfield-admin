<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyVersionScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements the version half of {@see SurveyVersionScopedInterface};
 * use it alongside {@see ScopedToSurvey}.
 */
trait ScopedToSurveyVersion
{
    protected ?string $surveyVersion = null;

    public function setSurveyVersion(string $eTag): static
    {
        $this->surveyVersion = $eTag;

        return $this;
    }

    public function getSurveyVersion(): string
    {
        return $this->surveyVersion ?? throw MissingScopeException::for(static::class, 'surveyVersion');
    }
}
