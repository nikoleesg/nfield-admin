<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Illuminate\Support\Str;
use Nikoleesg\NfieldAdmin\Data\SurveyPublishStateData;
use Nikoleesg\NfieldAdmin\Enums\SurveyPackageTypeEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyPublishForceUpgradeEnum;
use Nikoleesg\NfieldAdmin\HttpClient;
use Spatie\LaravelData\Exceptions\CannotCreateData;

class SurveyPublishEndpoint
{
    protected string $resourcePath = 'v1/surveys/{surveyId}/publish';

    protected HttpClient $httpClient;

    public function __construct(string $surveyId)
    {
        $this->resourcePath = Str::replace('{surveyId}', $surveyId, $this->resourcePath);

        $this->httpClient = new HttpClient();
    }

    /**
     * This method gets the publish state of the survey
     * https://apiap.nfieldmr.com/swagger/index.html#/Survey%20-%20Publish/get_v1_surveys__surveyId__publish
     */
    public function show(): ?SurveyPublishStateData
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $body = json_decode($response->body(), true);

        try {
            $surveyPublishStateData = SurveyPublishStateData::from($body);
        } catch (CannotCreateData $exception) {
            $surveyPublishStateData = SurveyPublishStateData::optional(null);
        }

        return $surveyPublishStateData;
    }

    /**
     * Publishes the survey to the survey package
     * https://apiap.nfieldmr.com/swagger/index.html#/Survey%20-%20Publish/put_v1_surveys__surveyId__publish
     */
    public function update(SurveyPackageTypeEnum $packageType, SurveyPublishForceUpgradeEnum $forceUpgrade): bool
    {
        $resourcePath = $this->resourcePath;

        $properties = [
            'packageType' => $packageType->value,
            'forceUpgrade' => $forceUpgrade->value,
        ];

        $response = $this->httpClient->put($resourcePath, true, $properties);

        return $response->ok();
    }
}
