<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyInterviewEndpointInterface
{
    /**
     * Delete all data for a specified interview of a specified survey
     */
    public function deleteInterviewData(string $surveyId, string $interviewId): array;
}
