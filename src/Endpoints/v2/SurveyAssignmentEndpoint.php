<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyAssignmentEndpointInterface;

class SurveyAssignmentEndpoint extends BaseEndpoint implements SurveyAssignmentEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/$this->version/surveys";
    }

    public function massAssign(string $surveyId, array $data): array
    {
        $url = $this->resourceActionPath($surveyId, 'samplingPointsAssignments');

        return $this->httpClient->post($url, $data)->json();
    }

    public function massUnassign(string $surveyId, array $data): array
    {
        $url = $this->resourceActionPath($surveyId, 'samplingPointsAssignments');

        return $this->httpClient->delete($url, $data)->json() ?? [];
    }
}
