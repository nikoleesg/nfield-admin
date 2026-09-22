<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointCollectionEndpointInterface;

final class SamplingPointCollectionEndpoint extends BaseEndpoint implements SamplingPointCollectionEndpointInterface
{
    protected function buildPath(): string
    {
        return "/$this->version/surveys";
    }

    public function list(string $surveyId): array
    {
        return $this->find($surveyId);
    }

    public function find(string $surveyId, array $data = []): array
    {
        $uri = $this->subResourcePath($surveyId, 'samplingPoints');

        return $this->httpClient->get($uri, $data)->json();
    }

    public function create(string $surveyId, array $samplingPointCreateRequestModel): array
    {
        $uri = $this->subResourcePath($surveyId, 'samplingPoints');

        return $this->httpClient->post($uri, $samplingPointCreateRequestModel)->json();
    }

    /**
     * Activated a list of spare sampling points so they can be assigned.
     */
    public function batchActivate(string $surveyId, array $samplingPointIds): array
    {
        $uri = $this->resourceActionPath($surveyId, 'activateSamplingpoints');

        return $this->httpClient->post($uri, ['samplingPointIds' => $samplingPointIds])->json();
    }
}
