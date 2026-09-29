<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersAssignmentsEndpointInterface;

final class CapiInterviewersAssignmentsEndpoint extends BaseEndpoint implements CapiInterviewersAssignmentsEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/capiInterviewers";
    }

    /**
     * Get assignments for a CAPI interviewer
     */
    public function list(string $interviewerId): array
    {
        $uri = $this->subResourcePath($interviewerId, 'assignments');

        return $this->unwrapList($this->httpClient->get($uri)->json());
    }
}
