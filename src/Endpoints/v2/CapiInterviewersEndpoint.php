<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\EditCapiInterviewerRequestData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\ResetCapiInterviewerPasswordRequestData;
use Nikoleesg\NfieldAdmin\Services\Http\ResponseKeyNormalizer;

final class CapiInterviewersEndpoint extends BaseEndpoint implements CapiInterviewersEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/capiInterviewers";
    }

    /**
     * Get a specific CAPI interviewer
     */
    public function get(string $interviewerId): array
    {
        $uri = $this->resourcePath($interviewerId);

        return $this->httpClient->get($uri)->json();
    }

    /**
     * Delete a CAPI interviewer
     */
    public function delete(string $interviewerId): void
    {
        $uri = $this->resourcePath($interviewerId);

        $this->httpClient->delete($uri);
    }

    /**
     * Update (partial) a CAPI interviewer via PATCH
     */
    public function update(string $interviewerId, EditCapiInterviewerRequestData $data): array
    {
        $uri = $this->resourcePath($interviewerId);

        $payload = array_filter(
            $data->toArray(),
            static fn (mixed $value): bool => $value !== null
        );

        return $this->httpClient->patch($uri, $payload)->json();
    }

    /**
     * Reset a CAPI interviewer's password via PUT
     */
    public function resetPassword(string $interviewerId, ResetCapiInterviewerPasswordRequestData $data): array
    {
        $uri = $this->resourcePath($interviewerId);

        return $this->httpClient->put($uri, $data->toArray())->json();
    }

    /**
     * Get assignments for a CAPI interviewer
     */
    public function getAssignments(string $interviewerId): array
    {
        $uri = $this->subResourcePath($interviewerId, 'assignments');

        return $this->unwrapList($this->httpClient->get($uri)->json());
    }

    /**
     * Get offices for a CAPI interviewer
     */
    public function getOffices(string $interviewerId): array
    {
        $uri = $this->subResourcePath($interviewerId, 'offices');

        return $this->httpClient->get($uri)->json();
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

    /**
     * Add a fieldwork office to an interviewer (PATCH)
     */
    public function updateOffice(string $interviewerId, string $officeId): void
    {
        $uri = $this->subResourceItemPath($interviewerId, 'offices', $officeId);

        $this->httpClient->patch($uri, []);
    }

    /**
     * Delete an office assignment
     */
    public function deleteOffice(string $interviewerId, string $officeId): void
    {
        $uri = $this->subResourceItemPath($interviewerId, 'offices', $officeId);

        $this->httpClient->delete($uri);
    }
}
