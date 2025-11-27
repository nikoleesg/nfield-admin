<?php

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointResponseModel;
use Nikoleesg\NfieldAdmin\Services\SamplingPointAddressService;
use Nikoleesg\NfieldAdmin\Services\SamplingPointAssignmentService;
use Nikoleesg\NfieldAdmin\Services\SamplingPointQuotaTargetsService;

class SamplingPointResource
{
    protected ?string $surveyId = null;
    protected ?string $samplingPointId = null;

    protected ?SamplingPointAddressService $samplingPointAddressService = null;
    protected ?SamplingPointAssignmentService $samplingPointAssignmentService = null;

    public function __construct(
        protected SamplingPointEndpointInterface $samplingPointEndpoint
    ) {}

    public function setSurveyId(string $surveyId): SamplingPointResource
    {
        $this->surveyId = $surveyId;
        return $this;
    }

    public function setSamplingPointId(string $samplingPointId): SamplingPointResource
    {
        $this->samplingPointId = $samplingPointId;
        return $this;
    }

    public function getSamplingPoint(): SamplingPointResponseModel
    {
        return SamplingPointResponseModel::from($this->samplingPointEndpoint->get($this->surveyId, $this->samplingPointId));
    }

    public function deleteSamplingPoint(): bool
    {
        return $this->samplingPointEndpoint->delete($this->surveyId, $this->samplingPointId);
    }

    public function updateSamplingPoint(array $data = []): array
    {
        return $this->samplingPointEndpoint->update($this->surveyId, $this->samplingPointId, $data);
    }

    public function activateSamplingPoint()
    {
        // TODO
    }


    public function replaceSamplingPoint(array $data = [])
    {
        // TODO
    }

    /**
     * @return SamplingPointAddressService
     */
    public function addresses(): SamplingPointAddressService
    {
        return $this->resolveService(SamplingPointAddressService::class);
    }

    /**
     * @return SamplingPointAssignmentService
     */
    public function assignments(): SamplingPointAssignmentService
    {
        return $this->resolveService(SamplingPointAssignmentService::class);
    }

    /**
     * @return SamplingPointQuotaTargetsService
     */
    public function quotaTargets(): SamplingPointQuotaTargetsService
    {
        return $this->resolveService(SamplingPointQuotaTargetsService::class);
    }

    /**
     * Helper function to resolve service
     * @param string $serviceClass
     * @return mixed
     */
    protected function resolveService(string $serviceClass): mixed
    {
        // Lazy load with property caching
        $property = lcfirst(class_basename($serviceClass));

        $service = $this->$property ??= app($serviceClass);

        if ($this->surveyId !== null) {
            $service->setSurveyId($this->surveyId);
        }

        if ($this->samplingPointId !== null) {
            $service->setSamplingPointId($this->samplingPointId);
        }

        return $service;
    }
}
