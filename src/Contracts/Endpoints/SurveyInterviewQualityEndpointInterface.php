<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyInterviewQualityEndpointInterface
{
    public function get(string $surveyId, string $interviewId): array;
}
