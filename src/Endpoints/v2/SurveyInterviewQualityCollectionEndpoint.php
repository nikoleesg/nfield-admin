<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyInterviewQualityCollectionEndpointInterface;

final class SurveyInterviewQualityCollectionEndpoint extends BaseEndpoint implements SurveyInterviewQualityCollectionEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function get(string $surveyId): array
    {
        $uri = $this->subResourcePath($surveyId, 'interviewQuality');

        return $this->httpClient->get($uri)->json();
    }

    public function updateQuality(string $surveyId, array $qualityNewStateChangeModel): array
    {
        $uri = $this->subResourcePath($surveyId, 'interviewQuality');

        return $this->httpClient->put($uri, $qualityNewStateChangeModel)->json();
    }
}
