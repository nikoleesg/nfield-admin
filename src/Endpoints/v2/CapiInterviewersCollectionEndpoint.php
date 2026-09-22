<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Services\Http\ResponseKeyNormalizer;

final class CapiInterviewersCollectionEndpoint extends BaseEndpoint implements CapiInterviewersCollectionEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/capiInterviewers";
    }

    /**
     * Get all CAPI interviewers
     */
    public function list(): array
    {
        $uri = $this->basePath();

        return $this->unwrapList($this->httpClient->get($uri)->json());
    }

    /**
     * Find CAPI interviewers with filters
     */
    public function find(array $data = []): array
    {
        $uri = $this->basePath();

        return $this->unwrapList($this->httpClient->get($uri, $data)->json());
    }

    /**
     * Create a new CAPI interviewer
     */
    public function create(array $newCapiInterviewerRequestData): array
    {
        $uri = $this->basePath();

        return $this->httpClient->post($uri, $newCapiInterviewerRequestData)->json();
    }

    /**
     * Get CAPI interviewer by client interviewer ID
     */
    public function getByClientId(string $clientInterviewerId): array
    {
        $uri = $this->actionPath("getByClientId/{$clientInterviewerId}");

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
}
