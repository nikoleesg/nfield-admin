<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersOfficesEndpointInterface;

final class CapiInterviewersOfficesEndpoint extends BaseEndpoint implements CapiInterviewersOfficesEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/capiInterviewers";
    }

    /**
     * Get the fieldwork offices a CAPI interviewer belongs to
     */
    public function list(string $interviewerId): array
    {
        $uri = $this->subResourcePath($interviewerId, 'offices');

        return $this->httpClient->get($uri)->json();
    }

    /**
     * Add a fieldwork office to an interviewer (PATCH)
     */
    public function update(string $interviewerId, string $officeId): void
    {
        $uri = $this->subResourceItemPath($interviewerId, 'offices', $officeId);

        $this->httpClient->patch($uri, []);
    }

    /**
     * Delete an office assignment
     */
    public function delete(string $interviewerId, string $officeId): void
    {
        $uri = $this->subResourceItemPath($interviewerId, 'offices', $officeId);

        $this->httpClient->delete($uri);
    }
}
