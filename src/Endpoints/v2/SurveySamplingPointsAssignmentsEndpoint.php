<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySamplingPointsAssignmentsEndpointInterface;

final class SurveySamplingPointsAssignmentsEndpoint extends BaseEndpoint implements SurveySamplingPointsAssignmentsEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function massAssign(string $surveyId, array $data): array
    {
        $uri = $this->subResourcePath($surveyId, 'samplingPointsAssignments');

        return $this->httpClient->post($uri, $data)->json();
    }

    public function massUnassign(string $surveyId, array $data): array
    {
        $uri = $this->subResourcePath($surveyId, 'samplingPointsAssignments');

        return $this->httpClient->delete($uri, $data)->json() ?? [];
    }
}
