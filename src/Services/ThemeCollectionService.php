<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ThemeCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityResponseModel;

/**
 * Template themes, reached through `NfieldManager::themes()`.
 *
 * Services mirror the endpoint naming: this pairs with ThemeCollectionEndpoint,
 * and {@see ThemeService} with ThemeEndpoint.
 */
class ThemeCollectionService
{
    public function __construct(
        protected ThemeCollectionEndpointInterface $themeCollectionEndpoint,
    ) {}

    /**
     * Create or replace the theme file of a template. The upload is processed
     * asynchronously; the response is the background activity to poll.
     */
    public function upload(string $templateId, string $themeName, string $contents, string $fileName): BackgroundActivityResponseModel
    {
        return BackgroundActivityResponseModel::from(
            $this->themeCollectionEndpoint->upload($templateId, $themeName, $contents, $fileName)
        );
    }

    /**
     * One theme.
     */
    public function forTheme(string $themeId): ThemeService
    {
        return app(ThemeService::class)->setThemeId($themeId);
    }
}
