<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyMoveEndpointInterface;

/**
 * `{surveyId}/surveyGroup`: the group a survey belongs to. Named after the
 * spec's "Survey Move" tag and SurveyMoveModel rather than the path, which
 * would give SurveySurveyGroupEndpoint.
 */
final class SurveyMoveEndpoint extends BaseEndpoint implements SurveyMoveEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function update(string $surveyId, array $surveyMoveModel): array
    {
        $uri = $this->subResourcePath($surveyId, 'surveyGroup');

        return $this->httpClient->put($uri, $surveyMoveModel)->json();
    }
}
