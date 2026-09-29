<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface ThemeCollectionEndpointInterface
{
    /**
     * The download URL of a theme.
     *
     * @return array<string, mixed>
     */
    public function downloadUrl(string $themeId): array;

    /**
     * Create or replace the theme file of a template under a theme name.
     *
     * @return array<string, mixed>
     */
    public function upload(string $templateId, string $themeName, string $contents, string $fileName): array;
}
