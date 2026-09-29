<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service that operates on one theme. The theme id is a string.
 */
interface ThemeScopedInterface
{
    public function setThemeId(string $themeId): static;

    public function getThemeId(): string;
}
