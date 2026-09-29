<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Services\SurveyQuotaFrameService;
use Nikoleesg\NfieldAdmin\Services\SurveyQuotaTargetsService;
use Nikoleesg\NfieldAdmin\Services\SurveyQuotaVersionsService;
use Nikoleesg\NfieldAdmin\Traits\ResolvesScopedServices;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * A survey's quota, reached through `$survey->quota()`.
 *
 * The frame, its targets and its versions are separate API resources, so each
 * has its own service; this resource only navigates to them.
 */
final class SurveyQuotaResource implements SurveyScopedInterface
{
    use ResolvesScopedServices;
    use ScopedToSurvey;

    public function frame(): SurveyQuotaFrameService
    {
        return $this->resolveService(SurveyQuotaFrameService::class);
    }

    public function targets(): SurveyQuotaTargetsService
    {
        return $this->resolveService(SurveyQuotaTargetsService::class);
    }

    public function versions(): SurveyQuotaVersionsService
    {
        return $this->resolveService(SurveyQuotaVersionsService::class);
    }
}
