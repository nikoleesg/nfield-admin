<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAssignmentEndpointInterface;

class SamplingPointAssignmentEndpoint extends BaseEndpoint implements SamplingPointAssignmentEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/$this->version/surveys";
    }

    public function list(string $surveyId, string $samplingPointId): array
    {
        $url = $this->nestedResourcePath($surveyId, 'samplingPoints', $samplingPointId, 'assignments');

        return $this->httpClient->get($url)->json();
    }

    public function assign(string $surveyId, string $samplingPointId, string $interviewerId): array
    {
        $url = $this->nestedResourceItemPath($surveyId, 'samplingPoints', $samplingPointId, 'assignments', $interviewerId);

        return $this->httpClient->post($url)->json();
    }

    public function unassign(string $surveyId, string $samplingPointId, string $interviewerId): bool
    {
        $url = $this->nestedResourceItemPath($surveyId, 'samplingPoints', $samplingPointId, 'assignments', $interviewerId);

        return $this->httpClient->delete($url)->getStatusCode() == 204;
    }
}
