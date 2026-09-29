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
    public function downloadInterview(string $surveyId, int $interviewId, array $surveyDataInterviewRequestModel): array;

    /**
     * Post a request for a data download
     *
     * @param  array<string, mixed>  $surveyDataRequestModel
     * @return array<string, mixed>
     */
    public function download(string $surveyId, array $surveyDataRequestModel): array;
}
