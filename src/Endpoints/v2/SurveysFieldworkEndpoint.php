<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Illuminate\Support\Str;
use Nikoleesg\NfieldAdmin\Enums\InterviewingRestrictionTypeEnum;
use Nikoleesg\NfieldAdmin\HttpClient;

class SurveysFieldworkEndpoint
{
    protected string $resourcePath = 'v2/surveys/{surveyId}/fieldwork';

    protected HttpClient $httpClient;

    public function __construct(string $surveyId)
    {
        $this->resourcePath = Str::replace('{surveyId}', $surveyId, $this->resourcePath);

        $this->httpClient = new HttpClient();
    }

    /**
     * This method stops the fieldwork of the survey
     * https://apiap.nfieldmr.com/swagger/index.html?urls.primaryName=V2#/Surveys%20-%20Fieldwork/put_v2_surveys__surveyId__fieldwork_stop
     */
    public function update(InterviewingRestrictionTypeEnum $restrictionType): bool
    {
        $resourcePath = $this->resourcePath . '/stop';

        $data = [
            'interviewingRestrictionType' => $restrictionType->value
        ];

        $response =  $this->httpClient->put($resourcePath, true, $data);

        return $response->noContent();
    }
}
