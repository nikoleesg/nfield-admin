<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersEndpointInterface;

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
    public function update(string $interviewerId, array $editCapiInterviewerRequestData): array
    {
        $uri = $this->resourcePath($interviewerId);

        $payload = array_filter(
            $editCapiInterviewerRequestData,
            static fn (mixed $value): bool => $value !== null
        );

        return $this->httpClient->patch($uri, $payload)->json();
    }

    /**
     * Reset a CAPI interviewer's password via PUT
     */
    public function resetPassword(string $interviewerId, array $resetCapiInterviewerPasswordRequestData): array
    {
        $uri = $this->resourcePath($interviewerId);

        return $this->httpClient->put($uri, $resetCapiInterviewerPasswordRequestData)->json();
    }
}
