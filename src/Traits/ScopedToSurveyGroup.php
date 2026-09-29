<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyGroupScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements {@see SurveyGroupScopedInterface}.
 */
trait ScopedToSurveyGroup
{
    protected ?int $surveyGroupId = null;

    public function setSurveyGroupId(int $surveyGroupId): static
    {
        $this->surveyGroupId = $surveyGroupId;

        return $this;
    }

    public function getSurveyGroupId(): int
    {
        return $this->surveyGroupId ?? throw MissingScopeException::for(static::class, 'surveyGroupId');
    }
}
