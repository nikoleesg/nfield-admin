<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ThemeEndpointInterface;

final class ThemeEndpoint extends BaseEndpoint implements ThemeEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/themes";
    }

    public function delete(string $themeId): void
    {
        $uri = $this->resourcePath($themeId);

        $this->httpClient->delete($uri);
    }
}
