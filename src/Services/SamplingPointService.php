<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointEndpointInterface;
use Nikoleesg\NfieldAdmin\Resources\SamplingPointResource;

class SamplingPointService
{
    protected ?string $surveyId = null;

    public function __construct(
        protected SamplingPointCollectionEndpointInterface $samplingPointCollectionEndpoint,
        protected SamplingPointEndpointInterface $samplingPointEndpoint
        // SamplingPointEndpoint
        // SamplingPointAddressCollectionEndpoint
        // SamplingPointAddressEndpoint
    ) {}

    public function setSurveyId(string $surveyId): self
    {
        $this->surveyId = $surveyId;

        return $this;
    }

    public function listSamplingPoints(): array
    {
        return $this->samplingPointCollectionEndpoint->find($this->surveyId);
    }

    public function findSamplingPoints(array $data = []): array
    {
        return $this->samplingPointCollectionEndpoint->find($this->surveyId, $data);
    }

    public function createSamplingPoint(array $data = []): array
    {
        return $this->samplingPointCollectionEndpoint->create($this->surveyId, $data);
    }

    public function activateSamplingPoints(array $samplingPointIds = []): void
    {
        $this->samplingPointCollectionEndpoint->batchActivate($this->surveyId, $samplingPointIds);
    }

    /**
     * Return SamplingPointResource for a specific survey samplingPoint
     * @param string $samplingPointId
     * @return SamplingPointResource
     */
    public function for(string $samplingPointId): SamplingPointResource
    {
        $samplingPointResource = new SamplingPointResource($this->samplingPointEndpoint);

        if ($this->surveyId !== null) {
            $samplingPointResource->setSurveyId($this->surveyId);
        }

        return $samplingPointResource->setSamplingPointId($samplingPointId);
    }
}
