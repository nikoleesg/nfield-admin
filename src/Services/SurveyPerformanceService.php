<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyPerformanceEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\Monitoring\SurveyMetricsModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * A survey's performance metrics, reached through `$survey->performance()`.
 *
 * Services mirror the endpoint naming: this pairs with SurveyPerformanceEndpoint.
 */
class SurveyPerformanceService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveyPerformanceEndpointInterface $surveyPerformanceEndpoint,
    ) {}

    /**
     * Metrics for live interviews.
     */
    public function live(): SurveyMetricsModel
    {
        return SurveyMetricsModel::from($this->surveyPerformanceEndpoint->live($this->getSurveyId()));
    }

    /**
     * Metrics for test interviews.
     */
    public function test(): SurveyMetricsModel
    {
        return SurveyMetricsModel::from($this->surveyPerformanceEndpoint->test($this->getSurveyId()));
    }
}
