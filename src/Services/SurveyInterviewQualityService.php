<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyInterviewQualityEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\InterviewQualityScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\InterviewDetailsModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToInterviewQuality;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * The quality record of one interview, reached through
 * `$survey->interviewQuality()->forInterview($interviewId)`.
 *
 * Services mirror the endpoint naming: this pairs with
 * SurveyInterviewQualityEndpoint, and {@see SurveyInterviewQualityCollectionService}
 * with SurveyInterviewQualityCollectionEndpoint.
 */
class SurveyInterviewQualityService implements InterviewQualityScopedInterface
{
    use ScopedToInterviewQuality;
    use ScopedToSurvey;

    public function __construct(
        protected SurveyInterviewQualityEndpointInterface $surveyInterviewQualityEndpoint,
    ) {}

    public function get(): InterviewDetailsModel
    {
        return InterviewDetailsModel::from(
            $this->surveyInterviewQualityEndpoint->get($this->getSurveyId(), $this->getQualityInterviewId())
        );
    }
}
