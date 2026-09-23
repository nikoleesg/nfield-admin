<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyInterviewQualityEndpointInterface;

final class SurveyInterviewQualityEndpoint extends BaseEndpoint implements SurveyInterviewQualityEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function get(string $surveyId, string $interviewId): array
    {
        $uri = $this->subResourceItemPath($surveyId, 'interviewQuality', $interviewId);

        return $this->httpClient->get($uri)->json();
    }
}
