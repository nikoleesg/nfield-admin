<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyDataEndpointInterface
{
    /**
     * Post a request for a data download of one interview
     *
     * @param  array<string, mixed>  $surveyDataInterviewRequestModel
     * @return array<string, mixed>
     */
    public function downloadInterviewData(string $surveyId, string $interviewId, array $surveyDataInterviewRequestModel): array;

    /**
     * Post a request for a data download
     *
     * @param  array<string, mixed>  $surveyDataRequestModel
     * @return array<string, mixed>
     */
    public function downloadData(string $surveyId, array $surveyDataRequestModel): array;
}
