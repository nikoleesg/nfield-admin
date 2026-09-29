<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\ThemeScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements {@see ThemeScopedInterface}.
 */
trait ScopedToTheme
{
    protected ?string $themeId = null;

    public function setThemeId(string $themeId): static
    {
        $this->themeId = $themeId;

        return $this;
    }

    public function getThemeId(): string
    {
        return $this->themeId ?? throw MissingScopeException::for(static::class, 'themeId');
    }
}
