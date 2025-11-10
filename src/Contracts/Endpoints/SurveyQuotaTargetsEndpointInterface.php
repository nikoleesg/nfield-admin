<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface  SurveyQuotaTargetsEndpointInterface
{
    public function getSurveyQuotaTarget(string $surveyId, ?int $eTag = null);
}
