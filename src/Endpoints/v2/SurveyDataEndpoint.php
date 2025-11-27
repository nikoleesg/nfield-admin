<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;


use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyDataEndpointInterface;

class SurveyDataEndpoint extends BaseEndpoint implements SurveyDataEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/$this->version/surveys";
    }

    /**
     * @param string $surveyId
     * @param string $interviewId
     * @param array $surveyDataInterviewRequestModel
     * @return array
     */
    public function downloadInterviewData(string $surveyId, string $interviewId, array $surveyDataInterviewRequestModel): array
    {
        $url = $this->subResourceItemPath($surveyId, 'dataDownload', $interviewId);

        return $this->httpClient->post($url, $surveyDataInterviewRequestModel)->json();
    }

    /**
     * @param string $surveyId
     * @param array $surveyDataRequestModel
     * @return array
     */
    public function downloadData(string $surveyId, array $surveyDataRequestModel): array
    {
        $url = $this->resourceActionPath($surveyId, 'dataDownload');

        return $this->httpClient->post($url, $surveyDataRequestModel)->json();
    }

    /**
     * @param string $surveyId
     * @param string $interviewId
     * @return array
     */
    public function deleteInterviewData(string $surveyId, string $interviewId): array
    {
        $url = $this->subResourceItemPath($surveyId, 'interviews', $interviewId);

        return $this->httpClient->delete($url)->json();
    }
}
