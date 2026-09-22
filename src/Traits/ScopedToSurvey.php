<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements {@see SurveyScopedInterface}.
 */
trait ScopedToSurvey
{
    protected ?string $surveyId = null;

    public function setSurveyId(string $surveyId): static
    {
        $this->surveyId = $surveyId;

        return $this;
    }

    public function getSurveyId(): string
    {
        return $this->surveyId ?? throw MissingScopeException::for(static::class, 'surveyId');
    }
}
