<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface ThemeEndpointInterface
{
    /**
     * Delete a theme.
     */
    public function delete(string $themeId): void;
}
