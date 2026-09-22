<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyInterviewEndpointInterface;

final class SurveyInterviewEndpoint extends BaseEndpoint implements SurveyInterviewEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function deleteInterviewData(string $surveyId, string $interviewId): array
    {
        $uri = $this->subResourceItemPath($surveyId, 'interviews', $interviewId);

        return $this->httpClient->delete($uri)->json();
    }
}
