<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyDataEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyInterviewEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyDataInterviewRequestModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyDataRequestModel;

class SurveyDataService
{
    public function __construct(
        protected SurveyDataEndpointInterface $surveyDataEndpoint,
        protected SurveyInterviewEndpointInterface $surveyInterviewEndpoint,
        protected readonly string $surveyId,
    ) {}

    public function downloadInterviewData(string $interviewId, string $fileName): BackgroundActivityStatus
    {
        $payload = SurveyDataInterviewRequestModel::from(['fileName' => $fileName])->toArray();

        return BackgroundActivityStatus::from(
            $this->surveyDataEndpoint->downloadInterviewData($this->surveyId, $interviewId, $payload)
        );
    }

    public function downloadData(array|SurveyDataRequestModel $data): BackgroundActivityStatus
    {
        $payload = SurveyDataRequestModel::from($data)->toArray();

        return BackgroundActivityStatus::from(
            $this->surveyDataEndpoint->downloadData($this->surveyId, $payload)
        );
    }

    public function deleteInterviewData(string $interviewId): BackgroundActivityStatus
    {
        return BackgroundActivityStatus::from(
            $this->surveyInterviewEndpoint->deleteInterviewData($this->surveyId, $interviewId)
        );
    }
}
