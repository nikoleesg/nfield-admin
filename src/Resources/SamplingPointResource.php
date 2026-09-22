<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ActivateSpareSamplingPointRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ActivateSpareSamplingPointsResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ReplaceSamplingPointWithSpareRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ReplaceSamplingPointWithSpareResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointUpdateRequestModel;
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

    public function setSurveyId(string $surveyId): static
    {
        $this->surveyId = $surveyId;
        $this->resolvedServices = [];

        return $this;
    }

    public function setSamplingPointId(string $samplingPointId): static
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

    public function updateSamplingPoint(array|SamplingPointUpdateRequestModel $data): SamplingPointResponseModel
    {
        $payload = SamplingPointUpdateRequestModel::from($data)->toArray();

        return SamplingPointResponseModel::from(
            $this->samplingPointEndpoint->update($this->surveyId, $this->samplingPointId, $payload)
        );
    }

    public function activateSamplingPoint(array|ActivateSpareSamplingPointRequestModel $data = []): ActivateSpareSamplingPointsResponseModel
    {
        $payload = ActivateSpareSamplingPointRequestModel::from($data)->toArray();

        return ActivateSpareSamplingPointsResponseModel::from(
            $this->samplingPointEndpoint->activate($this->surveyId, $this->samplingPointId, $payload)
        );
    }

    public function replaceSamplingPoint(array|ReplaceSamplingPointWithSpareRequestModel $data): ReplaceSamplingPointWithSpareResponseModel
    {
        $payload = ReplaceSamplingPointWithSpareRequestModel::from($data)->toArray();

        return ReplaceSamplingPointWithSpareResponseModel::from(
            $this->samplingPointEndpoint->replace($this->surveyId, $this->samplingPointId, $payload)
        );
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
