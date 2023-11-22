<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Nikoleesg\NfieldAdmin\Data\SamplingPointQuotaTargetData as QuotaTargetData;
use Nikoleesg\NfieldAdmin\HttpClient;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Exceptions\CannotCreateData;

class SamplingPointsQuotaTargetsEndpoint
{
    protected string $resourcePath = 'v1/Surveys/{surveyId}/SamplingPoints/{samplingPointId}/QuotaTargets';

    protected HttpClient $httpClient;

    public function __construct(string $surveyId, string $samplingPointId)
    {
        $this->resourcePath = str_replace(['{surveyId}', '{samplingPointId}'], [$surveyId, $samplingPointId], $this->resourcePath);

        $this->httpClient = new HttpClient();
    }

    /**
     * This method retrieves a list of quota level targets based on survey and sampling point.
     */
    public function index(): DataCollection
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $quotaTargets = json_decode($response->body(), true);

        return QuotaTargetData::collection($quotaTargets);
    }

    /**
     * This method retrieves detail of quota level targets based on survey and sampling point.
     */
    public function show(string $quotaLevelId): ?QuotaTargetData
    {
        $resourcePath = $this->resourcePath."/$quotaLevelId";

        $response = $this->httpClient->get($resourcePath);

        $quotaTarget = json_decode($response->body(), true);

        try {
            $quotaTargetData = QuotaTargetData::from($quotaTarget);

        } catch (CannotCreateData $exception) {
            $quotaTargetData = QuotaTargetData::optional(null);
        }

        return $quotaTargetData;
    }

    /**
     * Update an sampling point's quota level with the specified fields.
     * Only the Target property can be supplied.
     */
    public function update(string $quotaLevelId, int $target): ?QuotaTargetData
    {
        $resourcePath = $this->resourcePath."/$quotaLevelId";

        $response = $this->httpClient->patch($resourcePath, true, ['Target' => $target]);

        $quotaTarget = json_decode($response->body(), true);

        try {
            $quotaTargetData = QuotaTargetData::from($quotaTarget);

        } catch (CannotCreateData $exception) {
            $quotaTargetData = QuotaTargetData::optional(null);
        }

        return $quotaTargetData;
    }
}
