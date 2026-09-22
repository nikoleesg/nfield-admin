<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAddressCollectionEndpointInterface;

final class SamplingPointAddressCollectionEndpoint extends BaseEndpoint implements SamplingPointAddressCollectionEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/$this->version/surveys";
    }

    public function list(string $surveyId, string $samplingPointId): array
    {
        return $this->find($surveyId, $samplingPointId);
    }

    public function find(string $surveyId, string $samplingPointId, array $data = []): array
    {
        $uri = $this->nestedResourcePath($surveyId, 'samplingPoints', $samplingPointId, 'addresses');

        return $this->httpClient->get($uri, $data)->json();
    }

    public function create(string $surveyId, string $samplingPointId, array $addressModel): array
    {
        $uri = $this->nestedResourcePath($surveyId, 'samplingPoints', $samplingPointId, 'addresses');

        return $this->httpClient->post($uri, $addressModel)->json();
    }
}
