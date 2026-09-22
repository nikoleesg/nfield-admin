<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SamplingPointAssignmentEndpointInterface;

final class SamplingPointAssignmentEndpoint extends BaseEndpoint implements SamplingPointAssignmentEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function list(string $surveyId, string $samplingPointId): array
    {
        $uri = $this->nestedResourcePath($surveyId, 'samplingPoints', $samplingPointId, 'assignments');

        return $this->httpClient->get($uri)->json();
    }

    public function assign(string $surveyId, string $samplingPointId, string $interviewerId): array
    {
        $uri = $this->nestedResourceItemPath($surveyId, 'samplingPoints', $samplingPointId, 'assignments', $interviewerId);

        return $this->httpClient->post($uri, [])->json();
    }

    public function unassign(string $surveyId, string $samplingPointId, string $interviewerId): void
    {
        $uri = $this->nestedResourceItemPath($surveyId, 'samplingPoints', $samplingPointId, 'assignments', $interviewerId);

        $this->httpClient->delete($uri);
    }
}
