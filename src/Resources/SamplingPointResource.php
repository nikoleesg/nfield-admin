<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\SamplingPointScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ActivateSpareSamplingPointRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ActivateSpareSamplingPointsResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ReplaceSamplingPointWithSpareRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\ReplaceSamplingPointWithSpareResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SamplingPoints\SamplingPointUpdateRequestModel;
use Nikoleesg\NfieldAdmin\Services\SamplingPointAddressCollectionService;
use Nikoleesg\NfieldAdmin\Services\SamplingPointAssignmentService;
use Nikoleesg\NfieldAdmin\Services\SamplingPointQuotaTargetsService;
use Nikoleesg\NfieldAdmin\Services\SamplingPointService;
use Nikoleesg\NfieldAdmin\Traits\ResolvesScopedServices;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSamplingPoint;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SamplingPointResource implements SamplingPointScopedInterface
{
    use ResolvesScopedServices;
    use ScopedToSamplingPoint;
    use ScopedToSurvey;

    public function get(): SamplingPointResponseModel
    {
        return $this->item()->get();
    }

    /**
     * @param  array<string, mixed>|SamplingPointUpdateRequestModel  $data
     */
    public function update(array|SamplingPointUpdateRequestModel $data): SamplingPointResponseModel
    {
        return $this->item()->update($data);
    }

    public function delete(): void
    {
        $this->item()->delete();
    }

    /**
     * @param  array<string, mixed>|ActivateSpareSamplingPointRequestModel  $data
     */
    public function activate(array|ActivateSpareSamplingPointRequestModel $data = []): ActivateSpareSamplingPointsResponseModel
    {
        return $this->item()->activate($data);
    }

    /**
     * @param  array<string, mixed>|ReplaceSamplingPointWithSpareRequestModel  $data
     */
    public function replace(array|ReplaceSamplingPointWithSpareRequestModel $data): ReplaceSamplingPointWithSpareResponseModel
    {
        return $this->item()->replace($data);
    }

    public function addresses(): SamplingPointAddressCollectionService
    {
        return $this->resolveService(SamplingPointAddressCollectionService::class);
    }

    public function assignments(): SamplingPointAssignmentService
    {
        return $this->resolveService(SamplingPointAssignmentService::class);
    }

    public function quotaTargets(): SamplingPointQuotaTargetsService
    {
        return $this->resolveService(SamplingPointQuotaTargetsService::class);
    }

    private function item(): SamplingPointService
    {
        return $this->resolveService(SamplingPointService::class);
    }
}
