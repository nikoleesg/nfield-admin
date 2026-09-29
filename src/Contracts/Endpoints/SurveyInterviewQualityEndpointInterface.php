<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyInterviewQualityEndpointInterface
{
    /**
     * @return array<string, mixed>
     */
    public function get(string $surveyId, string $interviewId): array;
}
