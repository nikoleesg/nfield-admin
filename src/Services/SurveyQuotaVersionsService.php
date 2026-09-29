<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaVersionsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\Quota\QuotaFrameVersionModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * The survey's quota frame versions, reached through `$survey->quota()->versions()`.
 */
class SurveyQuotaVersionsService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveyQuotaVersionsEndpointInterface $surveyQuotaVersionsEndpoint,
    ) {}

    /** @return Collection<int, QuotaFrameVersionModel> */
    public function list(): Collection
    {
        return QuotaFrameVersionModel::collect(
            $this->surveyQuotaVersionsEndpoint->getQuotaVersions($this->getSurveyId()),
            Collection::class
        );
    }

    /**
     * One quota frame version, by the eTag the list returns.
     */
    public function forVersion(string $eTag): SurveyQuotaVersionService
    {
        return app(SurveyQuotaVersionService::class)
            ->setSurveyId($this->getSurveyId())
            ->setQuotaVersion($eTag);
    }
}
