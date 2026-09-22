<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressEndpointInterface;

final class SamplingPointAddressEndpoint extends BaseEndpoint implements SamplingPointAddressEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/$this->version/surveys";
    }

    public function get(string $surveyId, string $samplingPointId, string $addressId): array
    {
        $uri = $this->nestedResourceItemPath($surveyId, 'samplingPoints', $samplingPointId, 'addresses', $addressId);

        return $this->httpClient->get($uri)->json();
    }

    public function delete(string $surveyId, string $samplingPointId, string $addressId): void
    {
        $uri = $this->nestedResourceItemPath($surveyId, 'samplingPoints', $samplingPointId, 'addresses', $addressId);

        $this->httpClient->delete($uri);
    }
}
