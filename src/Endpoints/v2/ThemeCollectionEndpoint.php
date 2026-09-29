<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ThemeCollectionEndpointInterface;

final class ThemeCollectionEndpoint extends BaseEndpoint implements ThemeCollectionEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/themes";
    }

    public function downloadUrl(string $themeId): array
    {
        $uri = $this->basePath();

        return $this->httpClient->get($uri, ['themeId' => $themeId])->json();
    }

    public function upload(string $templateId, string $themeName, string $contents, string $fileName): array
    {
        $uri = $this->basePath();

        return $this->httpClient
            ->putMultipart($uri, 'File', $contents, $fileName, ['templateId' => $templateId, 'themeName' => $themeName])
            ->json();
    }
}
