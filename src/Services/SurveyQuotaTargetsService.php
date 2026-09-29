<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaTargetsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyQuota\SurveysQuotaTargetsResponseModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * The survey's quota targets, reached through `$survey->quota()->targets()`.
 */
class SurveyQuotaTargetsService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveyQuotaTargetsEndpointInterface $surveyQuotaTargetsEndpoint,
    ) {}

    /**
     * The targets of the current quota frame.
     */
    public function get(): SurveysQuotaTargetsResponseModel
    {
        return SurveysQuotaTargetsResponseModel::from(
            $this->surveyQuotaTargetsEndpoint->getQuotaTargets($this->getSurveyId())
        );
    }

    /**
     * The targets of one quota frame version.
     */
    public function forVersion(string $eTag): SurveyQuotaVersionTargetsService
    {
        return app(SurveyQuotaVersionTargetsService::class)
            ->setSurveyId($this->getSurveyId())
            ->setQuotaVersion($eTag);
    }
}
