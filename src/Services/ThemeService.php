<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ThemeCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ThemeEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\ThemeScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Templates\ThemeUrlResponseModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToTheme;

/**
 * One theme, reached through `NfieldManager::themes()->forTheme($themeId)`.
 *
 * Services mirror the endpoint naming: this pairs with ThemeEndpoint, and
 * {@see ThemeCollectionService} with ThemeCollectionEndpoint. The download URL
 * is read from the collection path with a themeId query, as the API defines it.
 */
class ThemeService implements ThemeScopedInterface
{
    use ScopedToTheme;

    public function __construct(
        protected ThemeEndpointInterface $themeEndpoint,
        protected ThemeCollectionEndpointInterface $themeCollectionEndpoint,
    ) {}

    public function downloadUrl(): ThemeUrlResponseModel
    {
        return ThemeUrlResponseModel::from($this->themeCollectionEndpoint->downloadUrl($this->getThemeId()));
    }

    public function delete(): void
    {
        $this->themeEndpoint->delete($this->getThemeId());
    }
}
