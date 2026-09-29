<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGroupDirectoryAssignmentsEndpointInterface;

final class SurveyGroupDirectoryAssignmentsEndpoint extends BaseEndpoint implements SurveyGroupDirectoryAssignmentsEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveyGroups";
    }

    public function list(int $surveyGroupId, array $query = []): array
    {
        $uri = $this->subResourcePath((string) $surveyGroupId, 'directoryAssignments');

        // The spec marks this list OData, so accept the envelope too.
        return $this->unwrapList($this->httpClient->get($uri, $query)->json());
    }
}
