<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\EditCapiInterviewerRequestData;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\ResetCapiInterviewerPasswordRequestData;

final class CapiInterviewersEndpoint extends BaseEndpoint implements CapiInterviewersEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/{$this->version}/capiInterviewers";
    }

    /**
     * Get a specific CAPI interviewer
     *
     * @param string $interviewerId
     * @return array
     */
    public function get(string $interviewerId): array
    {
        $uri = $this->resourcePath($interviewerId);

        return $this->normalizeItemResponse($this->httpClient->get($uri)->json());
    }

    /**
     * Delete a CAPI interviewer
     *
     * @param string $interviewerId
     * @return bool
     */
    public function delete(string $interviewerId): bool
    {
        $uri = $this->resourcePath($interviewerId);

        return $this->httpClient->delete($uri)->getStatusCode() === 204;
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

        return $this->normalizeItemResponse($this->httpClient->patch($uri, $payload)->json());
    }

    /**
     * Reset a CAPI interviewer's password via PUT
     */
    public function resetPassword(string $interviewerId, ResetCapiInterviewerPasswordRequestData $data): array
    {
        $uri = $this->resourcePath($interviewerId);

        return $this->normalizeItemResponse($this->httpClient->put($uri, $data->toArray())->json());
    }

    /**
     * Get assignments for a CAPI interviewer
     *
     * @param string $interviewerId
     * @return array
     */
    public function getAssignments(string $interviewerId): array
    {
        $uri = $this->subResourcePath($interviewerId, 'assignments');

        return $this->normalizeListResponse($this->httpClient->get($uri)->json());
    }

    /**
     * Get offices for a CAPI interviewer
     *
     * @param string $interviewerId
     * @return array
     */
    public function getOffices(string $interviewerId): array
    {
        $uri = $this->subResourcePath($interviewerId, 'offices');

        return $this->httpClient->get($uri)->json();
    }

    private function normalizeListResponse(mixed $json): array
    {
        if (!is_array($json)) {
            return [];
        }

        if (!array_is_list($json) && isset($json['value']) && is_array($json['value'])) {
            $json = $json['value'];
        }

        if (!array_is_list($json)) {
            return [];
        }

        return array_map([$this, 'normalizeItemResponse'], $json);
    }

    private function normalizeItemResponse(mixed $json): array
    {
        if (!is_array($json)) {
            return [];
        }

        $normalized = [];

        foreach ($json as $key => $value) {
            $normalized[is_string($key) ? lcfirst($key) : $key] = $value;
        }

        return $normalized;
    }

    /**
     * Add a fieldwork office to an interviewer (PATCH)
     */
    public function updateOffice(string $interviewerId, string $officeId): bool
    {
        $uri = $this->subResourceItemPath($interviewerId, 'offices', $officeId);

        $status = $this->httpClient->patch($uri, [])->getStatusCode();

        return $status === 201 || $status === 204;
    }

    /**
     * Delete an office assignment
     *
     * @param string $interviewerId
     * @param string $officeId
     * @return bool
     */
    public function deleteOffice(string $interviewerId, string $officeId): bool
    {
        $uri = $this->subResourceItemPath($interviewerId, 'offices', $officeId);

        $status = $this->httpClient->delete($uri)->getStatusCode();

        return $status === 200 || $status === 204;
    }
}
