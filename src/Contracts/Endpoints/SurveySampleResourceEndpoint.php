<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveySampleResourceEndpoint
{
    public function get(string $surveyId, int $interviewId): string;

}
