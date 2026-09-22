<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SamplingPointScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ActivateSpareSamplingPointRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ActivateSpareSamplingPointsResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ReplaceSamplingPointWithSpareRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ReplaceSamplingPointWithSpareResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointUpdateRequestModel;
use Nikoleesg\NfieldAdmin\Services\SamplingPointAddressService;
use Nikoleesg\NfieldAdmin\Services\SamplingPointAssignmentService;
use Nikoleesg\NfieldAdmin\Services\SamplingPointQuotaTargetsService;
use Nikoleesg\NfieldAdmin\Traits\ResolvesScopedServices;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSamplingPoint;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SamplingPointResource implements SamplingPointScopedInterface
{
    use ResolvesScopedServices;
    use ScopedToSamplingPoint;
    use ScopedToSurvey;

    public function __construct(
        protected SamplingPointEndpointInterface $samplingPointEndpoint
    ) {}

    public function getSamplingPoint(): SamplingPointResponseModel
    {
        return SamplingPointResponseModel::from($this->samplingPointEndpoint->get($this->getSurveyId(), $this->getSamplingPointId()));
    }

    public function deleteSamplingPoint(): void
    {
        $this->samplingPointEndpoint->delete($this->getSurveyId(), $this->getSamplingPointId());
    }

    public function updateSamplingPoint(array|SamplingPointUpdateRequestModel $data): SamplingPointResponseModel
    {
        $payload = SamplingPointUpdateRequestModel::from($data)->toArray();

        return SamplingPointResponseModel::from(
            $this->samplingPointEndpoint->update($this->getSurveyId(), $this->getSamplingPointId(), $payload)
        );
    }

    public function activateSamplingPoint(array|ActivateSpareSamplingPointRequestModel $data = []): ActivateSpareSamplingPointsResponseModel
    {
        $payload = ActivateSpareSamplingPointRequestModel::from($data)->toArray();

        return ActivateSpareSamplingPointsResponseModel::from(
            $this->samplingPointEndpoint->activate($this->getSurveyId(), $this->getSamplingPointId(), $payload)
        );
    }

    public function replaceSamplingPoint(array|ReplaceSamplingPointWithSpareRequestModel $data): ReplaceSamplingPointWithSpareResponseModel
    {
        $payload = ReplaceSamplingPointWithSpareRequestModel::from($data)->toArray();

        return ReplaceSamplingPointWithSpareResponseModel::from(
            $this->samplingPointEndpoint->replace($this->getSurveyId(), $this->getSamplingPointId(), $payload)
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
}
