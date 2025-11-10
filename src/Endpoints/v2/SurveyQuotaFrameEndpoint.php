<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyQuotaFrameEndpointInterface;

final class SurveyQuotaFrameEndpoint extends BaseEndpoint implements SurveyQuotaFrameEndpointInterface
{
    private string $version = 'v2';

    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function getSurveyQuotaFrame(string $surveyId)
    {
        $uri = $this->resourceActionPath($surveyId, 'surveyQuotaFrame');

        return $this->httpClient->get($uri)->json();
    }

    public function upsertSurveyQuotaFrame(string $surveyId, array $surveyQuotaFrameRequestModel)
    {

    }

    public function updateSurveyQuotaTarget(string $surveyId, int $eTag, array $surveyQuotaFrameEtagRequestModel)
    {

    }

}
