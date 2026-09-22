<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyDataEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyInterviewEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyDataInterviewRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyDataRequestModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SurveyDataService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveyDataEndpointInterface $surveyDataEndpoint,
        protected SurveyInterviewEndpointInterface $surveyInterviewEndpoint,
    ) {}

    public function downloadInterviewData(string $interviewId, string $fileName): BackgroundActivityStatus
    {
        $payload = SurveyDataInterviewRequestModel::from(['fileName' => $fileName])->toArray();

        return BackgroundActivityStatus::from(
            $this->surveyDataEndpoint->downloadInterviewData($this->getSurveyId(), $interviewId, $payload)
        );
    }

    public function downloadData(array|SurveyDataRequestModel $data): BackgroundActivityStatus
    {
        $payload = SurveyDataRequestModel::from($data)->toArray();

        return BackgroundActivityStatus::from(
            $this->surveyDataEndpoint->downloadData($this->getSurveyId(), $payload)
        );
    }

    public function deleteInterviewData(string $interviewId): BackgroundActivityStatus
    {
        return BackgroundActivityStatus::from(
            $this->surveyInterviewEndpoint->deleteInterviewData($this->getSurveyId(), $interviewId)
        );
    }
}
