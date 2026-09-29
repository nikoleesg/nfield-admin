<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyDataEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyInterviewEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\InterviewScopedInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyDataInterviewRequestModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToInterview;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * The data of one interview, reached through `$survey->data()->forInterview($id)`.
 */
class SurveyInterviewDataService implements InterviewScopedInterface
{
    use ScopedToInterview;
    use ScopedToSurvey;

    public function __construct(
        protected SurveyDataEndpointInterface $surveyDataEndpoint,
        protected SurveyInterviewEndpointInterface $surveyInterviewEndpoint,
    ) {}

    /**
     * Request a data download of this interview.
     *
     * The API names the file `surveyName_interviewId` when none is given.
     */
    public function download(?string $fileName = null): BackgroundActivityStatus
    {
        $payload = (new SurveyDataInterviewRequestModel($fileName))->toArray();

        return BackgroundActivityStatus::from(
            $this->surveyDataEndpoint->downloadInterviewData($this->getSurveyId(), $this->getInterviewId(), $payload)
        );
    }

    /**
     * Delete all data of this interview.
     */
    public function delete(): BackgroundActivityStatus
    {
        return BackgroundActivityStatus::from(
            $this->surveyInterviewEndpoint->deleteInterviewData($this->getSurveyId(), $this->getInterviewId())
        );
    }
}
