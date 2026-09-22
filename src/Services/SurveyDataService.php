<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyDataEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyInterviewEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyDataRequestModel;

class SurveyDataService
{
    public function __construct(
        protected SurveyDataEndpointInterface $surveyDataEndpoint,
        protected SurveyInterviewEndpointInterface $surveyInterviewEndpoint,
        protected readonly string $surveyId,
    ) {}

    public function downloadInterviewData(string $interviewId, string $fileName): array
    {
        $data = [
            'fileName' => $fileName,
        ];

        return $this->surveyDataEndpoint->downloadInterviewData($this->surveyId, $interviewId, $data);
    }

    public function downloadData(SurveyDataRequestModel $surveyDataRequestModel): BackgroundActivityStatus
    {
        return BackgroundActivityStatus::from(
            $this->surveyDataEndpoint->downloadData(
                $this->surveyId,
                $surveyDataRequestModel->toArray()
            )
        );
    }

    public function deleteInterviewData(string $interviewId): array
    {
        return $this->surveyInterviewEndpoint->deleteInterviewData($this->surveyId, $interviewId);
    }
}
