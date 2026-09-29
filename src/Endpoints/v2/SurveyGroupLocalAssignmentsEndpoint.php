<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGroupLocalAssignmentsEndpointInterface;

final class SurveyGroupLocalAssignmentsEndpoint extends BaseEndpoint implements SurveyGroupLocalAssignmentsEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveyGroups";
    }

    public function list(int $surveyGroupId): array
    {
        $uri = $this->subResourcePath((string) $surveyGroupId, 'localAssignments');

        return $this->httpClient->get($uri)->json();
    }
}
