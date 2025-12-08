<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyDataEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyDataRequestModel;

class SurveyDataService
{
    protected ?string $surveyId = null;

    public function __construct(
        protected SurveyDataEndpointInterface $surveyDataEndpoint,
    ) {}

    public function setSurveyId(string $surveyId): self
    {
        $this->surveyId = $surveyId;
        return $this;
    }

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
        return $this->surveyDataEndpoint->deleteInterviewData($this->surveyId, $interviewId);
    }

}
