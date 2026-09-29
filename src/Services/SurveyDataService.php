<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyDataEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyDataRequestModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SurveyDataService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveyDataEndpointInterface $surveyDataEndpoint,
    ) {}

    /**
     * Request a data download of the whole survey.
     *
     * @param  array<string, mixed>|SurveyDataRequestModel  $data
     */
    public function download(array|SurveyDataRequestModel $data): BackgroundActivityStatus
    {
        $payload = SurveyDataRequestModel::from($data)->toArray();

        return BackgroundActivityStatus::from(
            $this->surveyDataEndpoint->downloadData($this->getSurveyId(), $payload)
        );
    }

    /**
     * The data of one interview.
     */
    public function forInterview(int $interviewId): SurveyInterviewDataService
    {
        return app(SurveyInterviewDataService::class)
            ->setSurveyId($this->getSurveyId())
            ->setInterviewId($interviewId);
    }
}
