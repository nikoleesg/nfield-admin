<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface  SurveyQuotaFrameEndpointInterface
{
    public function getSurveyQuotaFrame(string $surveyId);

    public function upsertSurveyQuotaFrame(string $surveyId, array $surveyQuotaFrameRequestModel);

    public function updateSurveyQuotaTarget(string $surveyId, int $eTag, array $surveyQuotaFrameEtagRequestModel);
}
