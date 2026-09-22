<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersAssignmentsEndpointInterface;
use Nikoleesg\NfieldAdmin\Services\Http\ResponseKeyNormalizer;

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

    /**
     * Unwrap the OData `{"value": [...]}` envelope some list responses use.
     *
     * Key casing is already normalized at the HTTP boundary (see
     * {@see ResponseKeyNormalizer}).
     */
    private function unwrapList(mixed $json): array
    {
        if (! is_array($json)) {
            return [];
        }

        if (! array_is_list($json) && isset($json['value']) && is_array($json['value'])) {
            $json = $json['value'];
        }

        return array_is_list($json) ? $json : [];
    }
}
