<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\ParentSurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements {@see ParentSurveyScopedInterface}.
 */
trait ScopedToParentSurvey
{
    protected ?string $parentSurveyId = null;

    public function setParentSurveyId(string $parentSurveyId): static
    {
        $this->parentSurveyId = $parentSurveyId;

        return $this;
    }

    public function getParentSurveyId(): string
    {
        return $this->parentSurveyId ?? throw MissingScopeException::for(static::class, 'parentSurveyId');
    }
}
