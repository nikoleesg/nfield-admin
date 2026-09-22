<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointResponseModel;
use Nikoleesg\NfieldAdmin\Services\SamplingPointAddressService;
use Nikoleesg\NfieldAdmin\Services\SamplingPointAssignmentService;
use Nikoleesg\NfieldAdmin\Services\SamplingPointQuotaTargetsService;

class SamplingPointResource
{
    /** @var array<class-string, object> */
    protected array $resolvedServices = [];

    protected ?string $surveyId = null;

    protected ?string $samplingPointId = null;

    public function __construct(
        protected SamplingPointEndpointInterface $samplingPointEndpoint
    ) {}

    public function setSurveyId(string $surveyId): SamplingPointResource
    {
        $this->surveyId = $surveyId;
        $this->resolvedServices = [];

        return $this;
    }

    public function setSamplingPointId(string $samplingPointId): SamplingPointResource
    {
        $this->samplingPointId = $samplingPointId;
        $this->resolvedServices = [];

        return $this;
    }

    public function getSamplingPoint(): SamplingPointResponseModel
    {
        return SamplingPointResponseModel::from($this->samplingPointEndpoint->get($this->surveyId, $this->samplingPointId));
    }

    public function deleteSamplingPoint(): void
    {
        $this->samplingPointEndpoint->delete($this->surveyId, $this->samplingPointId);
    }

    public function updateSamplingPoint(array $data = []): array
    {
        return $this->samplingPointEndpoint->update($this->surveyId, $this->samplingPointId, $data);
    }

    public function activateSamplingPoint(array $data = []): array
    {
        return $this->samplingPointEndpoint->activate($this->surveyId, $this->samplingPointId, $data);
    }

    public function replaceSamplingPoint(array $data): array
    {
        return $this->samplingPointEndpoint->replace($this->surveyId, $this->samplingPointId, $data);
    }

    public function addresses(): SamplingPointAddressService
    {
        return $this->resolveService(SamplingPointAddressService::class);
    }

    public function assignments(): SamplingPointAssignmentService
    {
        return $this->resolveService(SamplingPointAssignmentService::class);
    }

    public function quotaTargets(): SamplingPointQuotaTargetsService
    {
        return $this->resolveService(SamplingPointQuotaTargetsService::class);
    }

    /**
     * Helper function to resolve service
     */
    protected function resolveService(string $serviceClass): mixed
    {
        if (isset($this->resolvedServices[$serviceClass])) {
            return $this->resolvedServices[$serviceClass];
        }

        $parameters = [];

        if (property_exists($this, 'surveyId') && $this->surveyId !== null) {
            $parameters['surveyId'] = $this->surveyId;
        }

        if (property_exists($this, 'samplingPointId') && $this->samplingPointId !== null) {
            $parameters['samplingPointId'] = $this->samplingPointId;
        }

        $service = app($serviceClass, $parameters);

        $this->resolvedServices[$serviceClass] = $service;

        return $service;
    }
}
