<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyDataEndpointInterface;

final class SurveyDataEndpoint extends BaseEndpoint implements SurveyDataEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function downloadInterview(string $surveyId, int $interviewId, array $surveyDataInterviewRequestModel): array
    {
        $uri = $this->subResourceItemPath($surveyId, 'dataDownload', $interviewId);

        return $this->httpClient->post($uri, $surveyDataInterviewRequestModel)->json();
    }

    public function download(string $surveyId, array $surveyDataRequestModel): array
    {
        $uri = $this->subResourcePath($surveyId, 'dataDownload');

        return $this->httpClient->post($uri, $surveyDataRequestModel)->json();
    }
}
