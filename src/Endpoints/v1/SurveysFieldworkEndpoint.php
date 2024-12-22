<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Illuminate\Support\Str;
use Nikoleesg\NfieldAdmin\Data\SurveyFieldworkCountsDTO;
use Nikoleesg\NfieldAdmin\Enums\SurveyFieldworkStatusEnum;
use Nikoleesg\NfieldAdmin\HttpClient;
use Spatie\LaravelData\Exceptions\CannotCreateData;

class SurveysFieldworkEndpoint
{
    protected string $resourcePath = 'v1/surveys/{surveyId}/fieldwork';

    protected HttpClient $httpClient;

    public function __construct(string $surveyId)
    {
        $this->resourcePath = Str::replace('{surveyId}', $surveyId, $this->resourcePath);

        $this->httpClient = new HttpClient();
    }

    /**
     * This method starts the fieldwork of the survey
     * https://apiap.nfieldmr.com/swagger/index.html#/Surveys%20-%20Fieldwork/put_v1_surveys__surveyId__fieldwork_start
     */
    public function update(): bool
    {
        $resourcePath = $this->resourcePath . '/start';

        $response = $this->httpClient->put($resourcePath, true);

        return $response->ok();
    }

    /**
     * This method returns fieldwork status
     * https://apiap.nfieldmr.com/swagger/index.html#/Surveys%20-%20Fieldwork/get_v1_surveys__surveyId__fieldwork_status
     */
    public function show(): ?SurveyFieldworkStatusEnum
    {
        $resourcePath = $this->resourcePath . '/status';

        $response = $this->httpClient->get($resourcePath);

        $fieldworkStatus = json_decode($response->body(), true);

        return SurveyFieldworkStatusEnum::tryFrom($fieldworkStatus);
    }

    /**
     * This method returns survey fieldwork counts
     * https://apiap.nfieldmr.com/swagger/index.html#/Surveys%20-%20Fieldwork/get_v1_surveys__surveyId__fieldwork_counts
     */
    public function count(): ?SurveyFieldworkCountsDTO
    {
        $resourcePath = $this->resourcePath . '/counts';

        $response = $this->httpClient->get($resourcePath);

        $fieldworkCounts = json_decode($response->body(), true);

        try {
            $fieldworkCountsData = SurveyFieldworkCountsDTO::from($fieldworkCounts);
        } catch (CannotCreateData $exception) {
            $fieldworkCountsData = SurveyFieldworkCountsDTO::optional(null);
        }

        return $fieldworkCountsData;
    }

}
