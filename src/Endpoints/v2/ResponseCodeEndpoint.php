<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ResponseCodeEndpointInterface;

final class ResponseCodeEndpoint extends BaseEndpoint implements ResponseCodeEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/responseCodes";
    }

    public function update(int $responseCodeId, array $domainResponseCodeUpdateModel): array
    {
        $uri = $this->resourcePath((string) $responseCodeId);

        return $this->httpClient->patch($uri, $domainResponseCodeUpdateModel)->json();
    }

    public function delete(int $responseCodeId): void
    {
        $uri = $this->resourcePath((string) $responseCodeId);

        $this->httpClient->delete($uri);
    }
}
