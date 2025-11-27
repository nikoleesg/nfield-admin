<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyDataEndpointInterface
{
    /**
     * Post a request for a data download of one interview
     * @param string $surveyId
     * @param string $interviewId
     * @param array $surveyDataInterviewRequestModel
     * @return array
     */
    public function downloadInterviewData(string $surveyId, string $interviewId, array $surveyDataInterviewRequestModel): array;

    /**
     * Post a request for a data download
     * @param string $surveyId
     * @param array $surveyDataRequestModel
     * @return array
     */
    public function downloadData(string $surveyId, array $surveyDataRequestModel): array;

    /**
     * Delete all data for a specified interview of a specified survey
     * @param string $surveyId
     * @param string $interviewId
     * @return array
     */
    public function deleteInterviewData(string $surveyId, string $interviewId): array;
}
