<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\UserRoleEndpointInterface;

final class UserRoleEndpoint extends BaseEndpoint implements UserRoleEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/me";
    }

    public function get(): array
    {
        $uri = $this->actionPath('role');

        return $this->httpClient->get($uri)->json();
    }
}
