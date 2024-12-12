<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Nikoleesg\NfieldAdmin\Data\SurveyData;
use Nikoleesg\NfieldAdmin\Endpoints\BaseEndpoint;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Data\SurveyUpdateSampleRecordDTO;

class SurveyBlueprintsEndpoint extends BaseEndpoint
{
    protected string $resourcePath = 'v1/Surveys/createSurveyFromBlueprint';

    /**
     * This method creates a new survey from a blueprint survey.
     * All survey configurations will be copied from the blueprint to the new survey.
     * https://api.nfieldmr.com/swagger/index.html#/Survey%20Blueprints/post_v1_surveys_createSurveyFromBlueprint
     */
    public function store(string $surveyName, string $blueprintSurveyId): SurveyData
    {
        $resourcePath = $this->resourcePath;

        $data = [
            'SurveyName'        => $surveyName,
            'BlueprintSurveyId' => $blueprintSurveyId
        ];

        $response = $this->httpClient->post($resourcePath, true, $data);

        $survey = json_decode($response->body(), true);

        return SurveyData::from($survey);
    }
}
