<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyDataEndpointInterface
{
    /**
     * Post a request for a data download of one interview
     */
    public function downloadInterviewData(string $surveyId, string $interviewId, array $surveyDataInterviewRequestModel): array;

    /**
     * Post a request for a data download
     */
    public function downloadData(string $surveyId, array $surveyDataRequestModel): array;

    /**
     * Delete all data for a specified interview of a specified survey
     */
    public function deleteInterviewData(string $surveyId, string $interviewId): array;
}
