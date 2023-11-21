<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Nikoleesg\NfieldAdmin\Endpoints\BaseEndpoint;

class SurveysQuotaTargetsEndpoint extends BaseEndpoint
{
    protected string $resourcePath = 'v1/Surveys/{surveyId}/QuotaTargets';

    protected ?string $surveyId;

    public function __construct(?string $surveyId = null)
    {
        $this->resourcePath = str_replace(['{surveyId}'], [$surveyId], $this->resourcePath);

        parent::__construct();
    }

    public function index()
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $quotaTargets = json_decode($response->body(), true);

        return $quotaTargets;
    }
}
