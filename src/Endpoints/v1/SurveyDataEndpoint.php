<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Nikoleesg\NfieldAdmin\Endpoints\BaseEndpoint;
use Nikoleesg\NfieldAdmin\Data\SurveyDataRequestDTO;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivityDTO;

class SurveyDataEndpoint extends BaseEndpoint
{
    protected string $resourcePath = 'v1/Surveys/{surveyId}/DataDownload';

    protected ?string $surveyId;

    public function __construct(?string $surveyId = null)
    {
        $this->resourcePath = str_replace(['{surveyId}'], [$surveyId], $this->resourcePath);

        parent::__construct();
    }

    public function store(SurveyDataRequestDTO $surveyDataRequest)
    {
        $resourcePath = $this->resourcePath;

        $surveyDataRequestModel = array_change_key_casing($surveyDataRequest->toArray(), CASE_STUDLY);


        $response = $this->httpClient->post($resourcePath, true, $surveyDataRequestModel);

        $backgroundActivity = json_decode($response->body(), true);

        return $backgroundActivity;

    }

}
