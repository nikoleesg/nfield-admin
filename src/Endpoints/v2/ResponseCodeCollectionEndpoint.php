<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ResponseCodeCollectionEndpointInterface;

final class ResponseCodeCollectionEndpoint extends BaseEndpoint implements ResponseCodeCollectionEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/responseCodes";
    }

    public function list(): array
    {
        $uri = $this->basePath();

        return $this->httpClient->get($uri)->json();
    }

    public function create(array $domainResponseCodeCreateModel): array
    {
        $uri = $this->basePath();

        return $this->httpClient->post($uri, $domainResponseCodeCreateModel)->json();
    }
}
