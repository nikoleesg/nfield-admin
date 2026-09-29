<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyInterviewEndpointInterface
{
    /**
     * Delete all data for a specified interview of a specified survey
     *
     * @return array<string, mixed>
     */
    public function deleteInterviewData(string $surveyId, int $interviewId): array;
}
