<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\RolesEndpointInterface;

final class RolesEndpoint extends BaseEndpoint implements RolesEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/roles";
    }

    public function list(): array
    {
        $uri = $this->basePath();

        return $this->httpClient->get($uri)->json();
    }
}
